<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory;
    
    protected $table = 'order_products';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }

    public function getCodeAttribute($value){
        if ($this->order->status == 0){
            return $this->getProduct()->code;
        }
        else return $value;
    }
    public function getNameAttribute($value){
        if ($this->order->status == 0){
            return $this->getProduct()->name;
        }
        else return $value;
    }
    public function getPriceAttribute($value){
        if ($this->order->status == 0){
            return $this->getProduct()->price;
        }
        else return $value;
    }
    public function getCurrencyAttribute($value){
        if ($this->order->status == 0){
            return $this->getProduct()->currency;
        }
        else return $value;
    }
    public function getUnitAttribute($value){
        if ($this->order->status == 0){
            return $this->getProduct()->unit;
        }
        else return $value;
    }

    public function getProduct(){
        $order_product = $this;
        $product = Product::find($order_product->product_id);
        if ($product === null) return null;
        return $product;
    }

    public function getTotalPrice($user){
        if ($user === null) return null;
        $order_product = $this;
        return $order_product->getPriceWithDiscount($user) * $order_product->quantity;
    }

    public function getPriceWithDiscount($user){
        if ($user === null) return null;
        $order_product = $this;
        if ($order_product->order->status == 0){
            $product = $order_product->getProduct();
            if ($product === null) return null;
            return $product->getPriceWithDiscount($user);
        }
        else if ($order_product->order->status > 0){
            if ($order_product->price === null || $order_product->discount === null) return null;
            else return $order_product->price * (1 - $order_product->discount/100);
        }
    }

    public function getDiscount($user){
        if ($user === null) return null;
        $order_product = $this;
        if ($this->order->status == 0){
            $product = $order_product->getProduct();
            if ($product === null) return null;
            return $product->getDiscount($user);
        }
        else if ($this->order->status > 0) return $this->discount;
    }
}
