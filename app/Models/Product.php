<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RelatedProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function files()
    {
        return $this->belongsToMany('App\Models\File', 'product_files');
    }

    public function relatedProducts()
    {
        return $this->hasMany('App\Models\RelatedProduct', 'product_id');
    }

    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function parameters()
    {
        return $this->hasMany('App\Models\ProductParameter');
    }


    public function getDiscount($user)
    {
        if ($user === null) return null;
        $product = $this;
        $category = $product->category;
        $discount = $user->getDiscount($category);
        return $discount;
    }

    public function getPriceWithDiscount($user)
    {
        if ($user === null) return null;
        $product = $this;
        $discount = $product->getDiscount($user);
        return $product->price * (1 - $discount / 100);
    }

    public function getPriceWithGeneralDiscount()
    {
        $product = $this;
        $discount = $product->category->discount;
        return $product->price * (1 - $discount / 100);
    }

    public function safeDelete()
    {
        $files = $this->files;
        foreach ($files as $file) {
            $file->safeDelete();
        }
        $this->orderProducts()->delete();
        RelatedProduct::where('product_id', $this->id)->orWhere('related_product_id', $this->id)->delete();
        $this->parameters()->delete();
        $this->delete();
    }
}
