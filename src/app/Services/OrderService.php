<?php

namespace App\Services;

use App\Order;

class OrderService
{
    public function getUserSubmittedOrders($count)
    {
        return Order::where('user_id', auth()->user()->id)->where('status', '1')->orderBy('updated_at', 'desc')->take($count)->get();
    }

    public function getUserUnubmittedOrders($count)
    {
        return Order::where('user_id', auth()->user()->id)->where('status', '0')->orderBy('updated_at', 'desc')->take($count)->get();
    }

    public function getSubmittedOrders($count)
    {
        return Order::where('status', '1')->orderBy('updated_at', 'desc')->take($count)->get();
    }
}
