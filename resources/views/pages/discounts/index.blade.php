@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>{{ __('main.my_discounts') }}</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>

                <table class='table_main'>
                    <tr><th></th><th>{{ __('main.category') }}</th><th>{{ __('main.product_group') }}</th><th>{{ __('main.discount') }}</th></tr>
                    <?php $counter = 1; ?>
                    @foreach ($discounts as $discount)
                        <tr>
                            <td>{{$counter++}}.</td>
                            <td><a href="{{url($discount->category->getDisplayUrl())}}">{{$discount->category->name}}</td>
                            <td>{{$discount->discount}}%</td>
                        </tr>
                    @endforeach
                </table>

        </div>
    </div>
</div>
@endsection