<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Order;
use App\Product;
use App\User;
use App\Mail\OrderMail;

class OrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (auth()->user()->isClient()){
            $orders = Order::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->get();
            return view('pages.orders.index') -> with('orders', $orders);
        }
        else if (auth()->user()->isAdmin()){
            $orders = Order::orderBy('updated_at', 'desc')->get();
            foreach ($orders as $order){
                if ($order->getClient() === null) abort(404);
                $order->client = $order->getClient();
            }
            return view('pages.orders.index') -> with('orders', $orders);
        }
        else abort(404);
    }

    public function indexByStatus($status){
        if (auth()->user()->isClient()){
            $orders = Order::where('user_id', auth()->user()->id)->where('status', $status)->orderBy('updated_at', 'desc')->get();
            return view('pages.orders.index') -> with('orders', $orders);
        }
        else if (auth()->user()->isAdmin()){
            $orders = Order::where('status', $status)->orderBy('updated_at', 'desc')->get();
            foreach ($orders as $order){
                if ($order->getClient() === null) abort(404);
                $order->client = $order->getClient();
            }
            return view('pages.orders.index') -> with('orders', $orders);
        }
        else abort(404);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isClient()){
            $order = new Order;
            $order->user_id = auth()->user()->id;
            $order->status = 0;
            $order->save();

            session(['current_order' => $order->id]);
            return redirect('/');
        }
        else abort(404);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if ($order === null) { abort(404); }
        
        if ((auth()->user()->isClient() && $order->user_id === auth()->user()->id) || auth()->user()->isAdmin()) {
            $user = User::find($order->user_id);
            if ($user === null || !$user->isClient()) { abort(404); }

            $data = array(
                'order_products' => $order->order_products,
                'order' => $order,
                'user' => $user
            );
            
            foreach ($data['order_products'] as $order_product) {
                if (($order->status == 0 && $order_product->getProduct() == null) || $order_product->getTotalPrice($user) == null) { abort(404); }
                $order_product->discount = $order_product->getDiscount($user);
                $order_product->price_discount = $order_product->getPriceWithDiscount($user);
                $order_product->total_price = $order_product->getTotalPrice($user);
            }

            $data['total_order_price_EUR'] = $order->getTotalOrderPrice($user, 'EUR');
            $data['total_order_price_USD'] = $order->getTotalOrderPrice($user, 'USD');

            return view('pages.orders.show')->with($data);
        }
        else abort(404);
    }

    public function edit($id)
    {
        if (auth()->user()->isClient()){
            session(['current_order' => $id]);
            return redirect('/');
        }
    }

    public function cancel(){
        if (auth()->user()->isClient()){
            session()->forget('current_order');
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->isClient()){
            $order = Order::find($id);
            if ($order === null || $order->user_id !== auth()->user()->id) { abort(404); }

            if ($order->status == 0){ 
                foreach ($order->order_products as $order_product) {
                    $product = $order_product->getProduct();
                    if ($product === null) { abort(404); }
                    $order_product->code = $product->code;
                    $order_product->name = $product->name;
                    $order_product->price = $product->price;
                    $order_product->currency = $product->currency;
                    $order_product->unit = $product->unit;
                    $order_product->discount = $product->getDiscount(auth()->user());
                    $order_product->product_id = null;
                    $order_product->save();
                }

                $order->status = 1;
                $order->save();
                session()->forget('current_order');
                $this->sendOrderEmails($order, auth()->user());
            }

            return redirect('/dashboard')->with('success', __('main.order_confirmation', ['order'=>$order->id, 'email'=>auth()->user()->email]));
        }
        else abort(404);
    }

    private function sendOrderEmails($order, $user)
    {
        Log::info("SENDING EMAIL | Order info email to admin | To: ".config('custom.company_info.email')." | Order ID: $order->id");
        Mail::to(config('custom.company_info.email'))->send(new OrderMail('admin', ['user' => $user, 'order' => $order]));
        Log::info("SENDING EMAIL | Order info email to client | To: $user->email | Order ID: $order->id");
        Mail::to($user->email)->send(new OrderMail('client', ['user' => $user, 'order' => $order]));
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order === null) abort(404);

        if ((auth()->user()->isClient() && $order->user_id === auth()->user()->id && $order->status === 0) || auth()->user()->isAdmin()){
            foreach ($order->order_products as $order_product){
                $order_product->delete();
            }
            if (session('current_order') == $order->id) session()->forget('current_order');
            $order->delete();
            
            return redirect('/dashboard');
        }
        else abort(404);
    }

    public function destroyUnsubmitted()
    {
        if (auth()->user()->isAdmin()){
            $unsubmittedOrders = Order::where('status', '0')->get();
            foreach ($unsubmittedOrders as $unsubmitted_order){
                foreach ($unsubmitted_order->order_products as $order_product){
                    $order_product->delete();
                }
                $unsubmitted_order->delete();
            }
            return redirect('/dashboard');
        }
        else abort(404);
    }
}
