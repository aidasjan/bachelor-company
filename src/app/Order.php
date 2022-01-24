<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\User;

class Order extends Model
{
    protected $table = 'orders';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function orderProducts()
    {
        return $this->hasMany('App\OrderProduct', 'order_id');
    }

    public function getTotalOrderPrice($user, $currency)
    {
        if ($user === null) return null;
        if ($currency === null) return null;
        $total_price = 0;
        $order = $this;
        foreach ($order->orderProducts as $order_product) {
            if ($order_product->currency == $currency) {
                $total_price += $order_product->getTotalPrice($user);
            }
        }
        return $total_price;
    }

    public function getStatus()
    {
        $order = $this;
        if (app()->getLocale() == 'ru') {
            switch ($order->status) {
                case 0:
                    return 'Незаконченный';
                case 1:
                    return 'Отправлено';
                case 2:
                    return 'Подтвердил';
                default:
                    return 'undefined';
            }
        } else {
            switch ($order->status) {
                case 0:
                    return 'Placing';
                case 1:
                    return 'Submitted';
                case 2:
                    return 'Confirmed';
                default:
                    return 'undefined';
            }
        }
    }

    public function getClient()
    {
        $order = $this;
        $user = User::find($order->user_id);
        if ($user !== null && $user->isClient()) return $user;
    }

    public function attachQuantities($products)
    {
        if ($products === null) return null;
        return $products->map(function($product) {
            return $this->attachQuantity($product);
        });
    }

    public function attachQuantity($product)
    {
        if ($product === null) return null;
        $orderProduct = $this->orderProducts->where('product_id', $product->id)->first();
        if ($orderProduct !== null) {
            $product->quantity = $orderProduct->quantity;
        } else $product->quantity = 0;
        return $product;
    }

    public function safeDelete()
    {
        $orderProducts = $this->orderProducts;
        foreach ($orderProducts as $orderProduct) {
            $orderProduct->delete();
        }
        $this->delete();
    }
}
