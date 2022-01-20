<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Discount;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->isNewClient()) return redirect('/password');

        if (auth()->user()->isClient()){
            $submitted_orders = Order::where('user_id', auth()->user()->id)->where('status', '1')->orderBy('updated_at', 'desc')->take(3)->get();
            $unsubmitted_orders = Order::where('user_id', auth()->user()->id)->where('status', '0')->orderBy('updated_at', 'desc')->take(3)->get();
            $discounts = Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->take(5)->get();
            
            $data = array(
                'submitted_orders'=>$submitted_orders,
                'unsubmitted_orders'=>$unsubmitted_orders,
                'discounts'=>$discounts
            );

            foreach ($data['discounts'] as $discount){
                $discount->subcategory_name = $discount->subcategory->name;
            }

            return view('pages.client.dashboard')->with($data);
        }

        else if (auth()->user()->isAdmin()) {
            $submitted_orders = Order::where('status', '1')->orderBy('updated_at', 'desc')->take(5)->get();
            foreach ($submitted_orders as $order){
                // Attach client to every order
                if ($order->getClient() === null) abort(404);
                $order->client = $order->getClient();
            }
            return view('pages.admin.dashboard')->with('submitted_orders', $submitted_orders);
        }

        else abort(404);
    }
}
