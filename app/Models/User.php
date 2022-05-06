<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\App;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $connection = 'mysql_gateway';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (App::runningUnitTests()) {
            $this->setConnection('sqlite');
        }
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id');
    }

    public function discounts()
    {
        return $this->hasMany('App\Models\Discount', 'user_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
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
        return $this->role === 'admin' &&
            $this->company &&
            $this->company->id == config('custom.company_info.id') &&
            !$this->isNew;
    }

    public function isClient()
    {
        return ($this->role === 'client' || ($this->role === 'admin' &&
            $this->company &&
            $this->company->id != config('custom.company_info.id'))
        ) && !$this->is_new;
    }

    public function isNewClient()
    {
        return $this->role === 'client' && $this->is_new;
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
