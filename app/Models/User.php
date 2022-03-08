<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Discount;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email_h', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id');
    }
    
    public function discounts()
    {
        return $this->hasMany('App\Models\Discount', 'user_id');
    }

    public function getEmailAttribute($value)
    {
        if (auth()->user() && (auth()->user()->isAdmin() || auth()->user()->id === $this->id))
            return decrypt($value);
        else return null;
    }

    public function getRoleAttribute($value)
    {
        return decrypt($value);
    }

    public function getNameAttribute($value)
    {
        if (auth()->user() && (auth()->user()->isAdmin() || auth()->user()->id === $this->id))
            return decrypt($value);
        else return null;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isClient()
    {
        return $this->role === 'client' && $this->is_new == 0;
    }

    public function isNewClient()
    {
        return $this->role === 'client' && $this->is_new == 1;
    }

    public function getAllDiscounts()
    {
        $user = $this;
        if (!($user->isClient() || $user->isNewClient())) return null;
        $discounts = Discount::where('user_id', $user->id)->get();
        return $discounts;
    }

    public function getDiscount($category)
    {
        $user = $this;
        if (!($user->isClient() || $user->isNewClient())) return null;
        $discount = $user->getAllDiscounts()->where('category_id', $category->id)->first();

        if ($category->discount > 0 && ($discount === null || $category->discount > $discount->discount))
            return $category->discount;

        if ($discount === null) return 0;
        return $discount->discount;
    }

    public function safeDelete()
    {
        $orders = $this->orders;
        foreach ($orders as $order) {
            $order->safeDelete();
        }
        $discounts = $this->discounts;
        foreach ($discounts as $discount) {
            $discount->delete();
        }
        $this->delete();
    }
}
