<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\OrderService;

class OrdersController extends Controller
{

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth');
        $this->orderService = $orderService;
    }

    public function index()
    {
        if (auth()->user()->isClient()) {
            $orders = $this->orderService->getUserOrders();
            return view('pages.orders.index')->with('orders', $orders);
        } else if (auth()->user()->isAdmin()) {
            $orders = $this->orderService->getOrders();
            return view('pages.orders.index')->with('orders', $orders);
        } else abort(404);
    }

    public function indexByStatus($status)
    {
        if (auth()->user()->isClient()) {
            $orders = $this->orderService->getUserOrdersByStatus($status, null);
            return view('pages.orders.index')->with('orders', $orders);
        } else if (auth()->user()->isAdmin()) {
            $orders = $this->orderService->getOrdersByStatus();
            return view('pages.orders.index')->with('orders', $orders);
        } else abort(404);
    }

    public function store()
    {
        if (auth()->user()->isClient()) {
            $this->orderService->newOrder();
            return redirect('/');
        } else abort(404);
    }

    public function storeOrderProducts(Request $request)
    {
        if (auth()->user()->isClient()) {
            if (!(session()->has('current_order'))) {
                abort(404);
            }
            $orderId = session('current_order');
            $this->orderService->storeOrderProducts($request, $orderId);
            return redirect('/');
        } else abort(404);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if ($order === null) {
            abort(404);
        }

        if ((auth()->user()->isClient() && $order->user_id === auth()->user()->id) || auth()->user()->isAdmin()) {
            $data = $this->orderService->getOrderDetails($order);
            return view('pages.orders.show')->with($data);
        } else abort(404);
    }

    public function edit($id)
    {
        if (auth()->user()->isClient()) {
            session(['current_order' => $id]);
            return redirect('/');
        }
    }

    public function cancel()
    {
        if (auth()->user()->isClient()) {
            session()->forget('current_order');
            return redirect('/');
        }
    }

    public function update($id)
    {
        if (auth()->user()->isClient()) {
            $order = $this->orderService->submit($id);
            return redirect('/dashboard')->with('success', __('main.order_confirmation', ['order' => $order->id, 'email' => auth()->user()->email]));
        } else abort(404);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order === null) abort(404);

        if ((auth()->user()->isClient() && $order->user_id === auth()->user()->id && $order->status === 0) || auth()->user()->isAdmin()) {
            $this->orderService->destroy($order);
            return redirect('/dashboard');
        } else abort(404);
    }

    public function destroyUnsubmitted()
    {
        if (auth()->user()->isAdmin()) {
            $this->orderService->destroyUnsubmitted();
            return redirect('/dashboard');
        } else abort(404);
    }
}
