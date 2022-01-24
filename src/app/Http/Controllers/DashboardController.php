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

        if (auth()->user()->isClient()) {
            $submittedOrders = Order::where('user_id', auth()->user()->id)->where('status', '1')->orderBy('updated_at', 'desc')->take(3)->get();
            $unsubmittedOrders = Order::where('user_id', auth()->user()->id)->where('status', '0')->orderBy('updated_at', 'desc')->take(3)->get();
            $discounts = Discount::where('user_id', auth()->user()->id)->orderBy('discount', 'desc')->take(5)->get();
            $data = array(
                'submittedOrders' => $submittedOrders,
                'unsubmittedOrders' => $unsubmittedOrders,
                'discounts' => $discounts
            );
            return view('pages.client.dashboard')->with($data);
        } else if (auth()->user()->isAdmin()) {
            $submittedOrders = Order::where('status', '1')->orderBy('updated_at', 'desc')->take(5)->get();
            foreach ($submittedOrders as $order) {
                if ($order->getClient() === null) abort(404);
                $order->client = $order->getClient();
            }
            return view('pages.admin.dashboard')->with('submittedOrders', $submittedOrders);
        } else abort(404);
    }
}
