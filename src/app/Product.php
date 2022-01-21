<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RelatedProduct;

class Product extends Model
{
    protected $table = 'products';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function files()
    {
        return $this->belongsToMany('App\File', 'product_files');
    }

    public function related_products()
    {
        return $this->hasMany('App\RelatedProduct', 'product_id');
    }

    public function order_products()
    {
        return $this->hasMany('App\OrderProduct');
    }


    public function getDiscount($user)
    {
        if ($user === null) return null;
        $product = $this;
        $subcategory = $product->subcategory;
        $discount = $user->getDiscount($subcategory);
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
        $order_products = $this->order_products;
        foreach ($order_products as $order_product) {
            $order_product->delete();
        }
        $relatedProducts = RelatedProduct::where('product_id', $this->id)->orWhere('related_product_id', $this->id)->get();
        foreach ($relatedProducts as $related_product) {
            $related_product->delete();
        }
        $this->delete();
    }
}
