@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col'>
            <h5>{{auth()->user()->name}} | {{auth()->user()->email}}</h5>
            <h1>ADMIN DASHBOARD</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col py-4 mx-3 dashboard_box container_lightblue'>
            <div class='row'>
                <div class='col text-left'>
                    <h3>Submitted Orders</h3>
                    <span>Orders that have been recently submitted by clients</span>
                </div>
                <div class='col text-right'>
                    <a class='btn btn-primary' href="{{url('/orders/status/1')}}">VIEW ALL</a>
                </div>
            </div>

            <div class='row py-3'>
                <div class='col'>
                    @if (count($submittedOrders) > 0)
                    <table class='table table_main'>
                        <?php $counter = 1 ?>
                        <tr><th></th><th>ORDER</th><th>CLIENT</th><th>DATE</th></tr>
                        @foreach ($submittedOrders as $order)
                            <tr>
                                <td>{{$counter++}}.</td>
                                <td><a href="{{url('/orders'.'/'.$order->id)}}">ORDER {{$order->id}}</td>
                                <td>{{$order->user->name}}</td>
                                <td>{{$order->updated_at}}</td>
                            </tr>
                        @endforeach
                    </table>
                    @else
                    <h5>No orders</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class='row'>
        <div class='col-md py-4 mx-3 my-3 dashboard_box container_lightblue'>
            <h3>Add New Client</h3>
            <div class='pb-3'><span>Add client and generate a password</span></div>
            <a href="{{url('/register')}}" class='btn btn-primary'>NEW CLIENT</a>
        </div>

        <div class='col-md py-4 mx-3 my-3 dashboard_box container_lightblue'>
            <h3>Users & Discounts</h3>
            <div class='pb-3'><span>Edit users, reset passwords, manage their personal discounts</span></div>
            <a href="{{url('/users')}}" class='btn btn-primary'>MANAGE USERS</a>
        </div>
    </div>

    <div class='row'>
        <div class='col-md py-4 mx-3 my-3 dashboard_box container_lightblue'>
            <h3>Import Data</h3>
            <div class='pb-3'><span>Upload a file to import Products and Categories</span></div>
            <a href="{{url('/import/upload/categories')}}" class='btn btn-primary m-2'>CATEGORIES</a>
            <a href="{{url('/import/upload/products')}}" class='btn btn-primary m-2'>PRODUCTS</a>
        </div>
    </div>

    <div class='row'>
        <div class='col py-4 mx-3 my-3 dashboard_box container_lightblue'>
            <h3>Additional settings</h3>
            <div class='row py-2'>
                <div class='col-md-8 py-2 text-left'>
                    <h5>View unsubmitted orders</h5>
                    <span>View all orders that haven't been submitted by clients yet</span>
                </div>
                <div class='col-md py-2 text-right'>
                    <a class='btn btn-primary' href="{{url('/orders/status/0')}}">UNSUBMITTED ORDERS</a>
                </div>
            </div>
            <hr>
            <div class='row py-2'>
                <div class='col-md-8 py-2 text-left'>
                    <h5>Clear all unsubmitted orders</h5>
                    <span>If data in database was altered (some products were deleted or changed) you should perform this action. Otherwise clients may get incorrect info or fail to submit orders.</span>
                </div>
                <div class='col-md py-2 text-right'>
                    <form action='{{ action('App\Http\Controllers\OrdersController@destroyUnsubmitted')}}' method='POST'>
                        {{csrf_field()}}
                        <button type='submit' class='btn btn-danger' href='#'>CLEAR ORDERS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection