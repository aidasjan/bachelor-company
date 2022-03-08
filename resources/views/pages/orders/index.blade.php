@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>{{ __('main.orders') }}</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col-md-10 offset-md-1'>

            <table class='table table_main'>
                <tr><th></th> @if (Auth::user()->isAdmin()) <th>CLIENT</th> @endif <th>{{ __('main.order') }}</th><th>{{ __('main.status') }}</th><th>{{ __('main.date') }}</th></tr>
                <?php $counter = 1; ?>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{$counter++}}.</td>
                        @if (Auth::user()->isAdmin()) <td>{{$order->getClient()->name}}</td> @endif
                        <td><a href="{{url('/orders'.'/'.$order->id)}}" class='text-uppercase'>{{ __('main.order') }} {{$order->id}}</td>
                        <td>{{$order->getStatus()}}</td>
                        <td>{{$order->updated_at}}</td>
                    </tr>
                @endforeach
            </table>
            
        </div>
    </div>
</div>
@endsection