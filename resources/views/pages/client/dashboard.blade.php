@extends('layouts.app')

@section('content')
    <div class='container'>

        <div class='row py-5'>
            <div class='col'>
                <h5>{{auth()->user()->name}} | {{auth()->user()->email}}</h5>
                <h1 class='text-uppercase'>{{ __('main.dashboard') }}</h1>
            </div>
        </div>

        <div class='row py-3'>
            <div class='col py-4 mx-3 dashboard_box container_lightblue'>
                <h3>{{ __('main.make_order') }}</h3>
                <span>{{ __('main.make_order_desc') }}</span>
                <form action="{{ action('App\Http\Controllers\OrdersController@store') }}" method='POST'>
                    {{csrf_field()}}
                    <button type='submit' class='btn btn-primary mt-4 mb-3 text-uppercase' href='#'>{{ __('main.new_order') }}</button>
                </form>
                <a href="{{url('/tutorial')}}" target='_blank' class='link_main'>{{ __('main.how_to_do') }}</a>
            </div>
        </div>

        <div class='row'>

            <div class='col-md mx-3'>

                <div class='row py-4'>
                    <div class='col py-2 dashboard_box container_lightblue'>
                        <div class='row'>
                            <div class='col text-left py-3'>
                                <h3>{{ __('main.unsubmittedOrders') }}</h3>
                                <span>{{ __('main.unsubmittedOrders_desc') }}</span>
                            </div>
                            <div class='col text-right py-3'>
                                <a class='btn btn-primary text-uppercase' href="{{url('/orders/status/0')}}">{{ __('main.view_all') }}</a>
                            </div>
                        </div>

                        <div class='row py-3'>
                            <div class='col'>
                                @if (count($unsubmittedOrders) > 0)
                                <table class='table table_main'>
                                    <?php $counter = 1 ?>
                                    <tr><th></th><th>{{ __('main.order') }}</th><th>{{ __('main.date') }}</th></tr>
                                    @foreach ($unsubmittedOrders as $order)
                                        <tr>
                                            <td>{{$counter++}}.</td>
                                            <td><a href="{{url('/orders'.'/'.$order->id)}}" class='text-uppercase'>{{ __('main.order') }} {{$order->id}}</td>
                                            <td>{{$order->updated_at}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @else
                                <h5>{{ __('main.no_orders') }}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class='row py-4'>
                    <div class='col py-2 dashboard_box container_lightblue'>
                        <div class='row'>
                            <div class='col text-left py-3'>
                                <h3>{{ __('main.submittedOrders') }}</h3>
                                <span>{{ __('main.submittedOrders_desc') }}</span>
                            </div>
                            <div class='col text-right py-3'>
                                <a class='btn btn-primary text-uppercase' href="{{url('/orders/status/1')}}">{{ __('main.view_all') }}</a>
                            </div>
                        </div>

                        <div class='row py-3'>
                            <div class='col'>
                                @if (count($submittedOrders) > 0)
                                <table class='table table_main'>
                                    <?php $counter = 1 ?>
                                    <tr><th></th><th>{{ __('main.order') }}</th><th>{{ __('main.date') }}</th></tr>
                                    @foreach ($submittedOrders as $order)
                                        <tr>
                                            <td>{{$counter++}}.</td>
                                            <td><a href="{{url('/orders'.'/'.$order->id)}}" class='text-uppercase'>{{ __('main.order') }} {{$order->id}}</td>
                                            <td>{{$order->updated_at}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @else
                                <h5>{{ __('main.no_orders') }}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class='col-md mx-3'>
                
                <div class='row py-4'>
                    <div class='col py-2 dashboard_box container_lightblue'>
                        <div class='row'>
                            <div class='col text-left py-3'>
                                <h3>{{ __('main.my_discounts') }}</h3>
                                <span>{{ __('main.my_discounts_desc') }}</span>
                            </div>
                            <div class='col text-right py-3'>
                                <a class='btn btn-primary text-uppercase' href="{{url('/discounts')}}">{{ __('main.view_all') }}</a>
                            </div>
                        </div>

                        <div class='row py-3'>
                            <div class='col'>
                                @if (count($discounts) > 0)
                                <table class='table table_main'>
                                    <?php $counter = 1 ?>
                                    <tr><th></th><th>{{ __('main.product_group') }}</th><th>{{ __('main.discount') }}</th></tr>
                                    @foreach ($discounts as $discount)
                                        <tr>
                                            <td>{{$counter++}}.</td>
                                            <td><a href="{{url($discount->category->getDisplayUrl())}}">{{$discount->category->name}}</td>
                                            <td>{{$discount->discount}}%</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @else
                                <h5>{{ __('main.no_discounts') }}</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class='row py-4'>
                    <div class='col py-2 dashboard_box container_lightblue'>
                        <div class='row py-3'>
                            <div class='col text-left'>
                                <h3>{{ __('main.my_account') }}</h3>
                                <span>{{ __('main.my_account_desc') }}</span>
                            </div>
                        </div>

                        <div class='row py-1'>
                            <div class='col text-left'>
                                <h5><b>{{ __('main.name_person') }}: </b>{{auth()->user()->name}}</h5>
                                <h5 class='pb-2'><b>{{ __('main.email') }}: </b>{{auth()->user()->email}}</h5>
                                <a href="{{url('/password')}}" class='link_main text-uppercase'>{{ __('main.change_password') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection