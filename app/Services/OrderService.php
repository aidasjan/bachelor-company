<?php

namespace App\Services;

use App\Mail\OrderMail;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    public function getUserOrders()
    {
        return Order::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
    }

    public function getUserOrdersByStatus($status, $count)
    {
        return Order::where('user_id', auth()->user()->id)->where('status', $status)->orderBy('updated_at', 'desc')->take($count)->get();
    }

    public function getUserSubmittedOrders($count)
    {
        return $this->getUserOrdersByStatus('1', $count);
    }

    public function getUserUnubmittedOrders($count)
    {
        return $this->getUserOrdersByStatus('0', $count);
    }

    public function getSubmittedOrders($count)
    {
        return Order::where('status', '1')->orderBy('updated_at', 'desc')->take($count)->get();
    }

    public function getOrdersByStatus($status)
    {
        return Order::where('status', $status)->orderBy('updated_at', 'desc')->get();
    }

    public function getOrders()
    {
        return Order::orderBy('updated_at', 'desc')->get();
    }

    public function newOrder()
    {
        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->status = 0;
        $order->save();
        session(['current_order' => $order->id]);
    }

    public function storeOrderProducts(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        if ($order === null || $order->user_id !== auth()->user()->id) {
            return null;
        }

        $inputs = $request->all();
        foreach ($inputs as $key => $value) {
            $product = Product::find($key);
            if ($product !== null) {
                if (!(is_numeric($value) || $value == '')) {
                    return null;
                }
                $orderProduct = $order->orderProducts->where('product_id', $key)->first();
                if ($orderProduct !== null) {
                    if ($value > 0) {
                        $orderProduct->quantity = $value;
                        $orderProduct->save();
                    } else {
                        $orderProduct->delete();
                    }
                } else {
                    if ($value > 0) {
                        $orderProduct = new OrderProduct;
                        $orderProduct->product_id = $key;
                        $orderProduct->quantity = $value;
                        $orderProduct->order_id = $order->id;
                        $orderProduct->save();
                    }
                }
            }
        }
        return $order;
    }

    public function getOrderDetails($order)
    {
        $user = User::find($order->user_id);
        if ($user === null || !$user->isClient()) {
            return null;
        }

        $data = array(
            'orderProducts' => $order->orderProducts,
            'order' => $order,
            'user' => $user
        );

        foreach ($data['orderProducts'] as $orderProduct) {
            if (($order->status == 0 && $orderProduct->getProduct() == null) || $orderProduct->getTotalPrice($user) == null) {
                abort(404);
            }
            $orderProduct->discount = $orderProduct->getDiscount($user);
            $orderProduct->price_discount = $orderProduct->getPriceWithDiscount($user);
            $orderProduct->total_price = $orderProduct->getTotalPrice($user);
        }

        $data['totalOrderPriceEUR'] = $order->getTotalOrderPrice($user, 'EUR');
        $data['totalOrderPriceUSD'] = $order->getTotalOrderPrice($user, 'USD');

        return $data;
    }

    public function submit($id)
    {
        $order = Order::find($id);
        if ($order === null || $order->user_id !== auth()->user()->id) {
            return null;
        }

        if ($order->status == 0) {
            foreach ($order->orderProducts as $orderProduct) {
                $product = $orderProduct->getProduct();
                if ($product === null) {
                    return null;
                }
                $orderProduct->code = $product->code;
                $orderProduct->name = $product->name;
                $orderProduct->price = $product->price;
                $orderProduct->currency = $product->currency;
                $orderProduct->unit = $product->unit;
                $orderProduct->discount = $product->getDiscount(auth()->user());
                $orderProduct->product_id = null;
                $orderProduct->save();
            }

            $order->status = 1;
            $order->save();
            session()->forget('current_order');
            $this->sendOrderEmails($order, auth()->user());
        }

        return $order;
    }

    private function sendOrderEmails($order, $user)
    {
        Log::info("SENDING EMAIL | Order info email to admin | To: " . config('custom.company_info.email') . " | Order ID: $order->id");
        Mail::to(config('custom.company_info.email'))->send(new OrderMail('admin', ['user' => $user, 'order' => $order]));
        Log::info("SENDING EMAIL | Order info email to client | To: $user->email | Order ID: $order->id");
        Mail::to($user->email)->send(new OrderMail('client', ['user' => $user, 'order' => $order]));
    }

    public function destroy($order)
    {
        if (session('current_order') == $order->id) {
            session()->forget('current_order');
        }
        $order->safeDelete();
    }

    public function destroyUnsubmitted()
    {
        $unsubmittedOrders = Order::where('status', '0')->get();
        foreach ($unsubmittedOrders as $unsubmittedOrder) {
            $unsubmittedOrder->safeDelete();
        }
        return redirect('/dashboard');
    }
}
