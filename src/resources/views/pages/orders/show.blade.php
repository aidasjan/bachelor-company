@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>{{ __('main.order') }} {{$order->id}} - {{$user->name}}</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>

            <table class='table table-responsive-md table_main'>
                <tr><th></th><th>{{ __('main.code') }}</th><th>{{ __('main.name') }}</th><th>{{ __('main.qty') }}</th><th>{{ __('main.unit') }}</th><th>{{ __('main.price') }}</th><th>{{ __('main.discount') }}</th><th>{{ __('main.final_price') }}</th><th>{{ __('main.total') }}</th></tr>
                <?php $counter = 1; ?>
                @foreach ($order_products as $order_product)
                    <tr>
                        <td>{{$counter++}}.</td>
                        <td>{{$order_product->code}}</td>
                        <td>{{$order_product->name}}</td>
                        <td>{{$order_product->quantity}}</td>
                        <td>{{$order_product->unit}}</td>
                        <td>{{number_format($order_product->price, 2, '.', '').' '.$order_product->currency}}</td>
                        <td>{{number_format($order_product->discount, 2, '.', '')}}%</td>
                        <td>{{number_format($order_product->price_discount, 2, '.', '').' '.$order_product->currency}}</td>
                        <td>{{number_format($order_product->total_price, 2, '.', '').' '.$order_product->currency}}</td>
                    </tr>
                @endforeach
                <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>{{ __('main.total') }}:</th>
                    <th>
                        @if ($total_order_price_EUR != 0 && $total_order_price_USD == 0)
                            {{number_format($total_order_price_EUR, 2, '.', '')}} EUR
                        @elseif ($total_order_price_EUR == 0 && $total_order_price_USD != 0)
                            {{number_format($total_order_price_USD, 2, '.', '')}} USD
                        @else
                            {{number_format($total_order_price_EUR, 2, '.', '')}} EUR + <br>{{number_format($total_order_price_USD, 2, '.', '')}} USD
                        @endif

                    </th>
                </tr>
            </table>
            
        </div>
    </div>

    @if (!Auth::guest() && Auth::user()->isClient() && $order->status === 0)
        <div class='my-3'>
            <form action='{{ action('OrdersController@update', $order->id)}}' method='POST'>
                <input type='hidden' name='_method' value='PUT'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary text-uppercase'>{{ __('main.submit_order') }}</button>
            </form>
        </div>
        <div class='my-3'>
            <a href={{url('/orders'.'/'.$order->id.'/edit')}} class='link_main text-uppercase'>{{ __('main.edit_order') }}</a>
        </div>
    @endif

    @if ((!Auth::guest() && Auth::user()->isClient() && $order->status === 0) || (!Auth::guest() && Auth::user()->isAdmin()))
        <div class='my-3'>
            <form action='{{ action('OrdersController@update', $order->id)}}' method='POST'>
                <input type='hidden' name='_method' value='DELETE'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-link link_red text-uppercase' href='#'>{{ __('main.discard_order') }}</button>
            </form>
        </div>
    @endif

</div>
@endsection