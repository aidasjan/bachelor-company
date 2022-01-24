@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row pt-5 pb-3'>
        <div class='col'>
            <h1 class='text-uppercase'>{{$headline}}</h1>
            @if (!empty($discount) && $discount > 0)<h2 class='text_green'>{{$discount}}% {{ __('main.discount') }}</h2><span class='text_green'>{{ __('main.discount_products_desc') }}</span> @endif
        </div>
    </div>


    @if (!Auth::guest() && Auth::user()->isAdmin() && !empty($category))
        <div class='row py-3 container_grey'>
            <div class='col'>
                <a href="{{url('/products/create/'.$category->id)}}" class='btn btn-link link_main'>ADD PRODUCT</a>
                <a href="{{url('/categories'.'/'.$category->id.'/edit')}}" class='btn btn-link link_main'>EDIT CATEGORY</a>
                <a href="{{url('/categories'.'/'.$category->id.'/images/create')}}" class='btn btn-link link_main'>ADD IMAGE</a>
                <a href="{{url('/reorder/products/'.$category->id)}}" class='btn btn-link link_main'>REORDER</a>
            </div>
        </div>
    @endif

    @if (!empty($categoryFiles))
        <div class='image_gallery d-flex flex-wrap justify-content-center py-2'>
            @foreach ($categoryFiles as $file)
                <div class='m-1'>
                    <img src='{{url('/files/images/'.$file->id)}}' />
                    @if (!Auth::guest() && Auth::user()->isAdmin())
                        <form action='{{ action('CategoriesController@destroyImage', $file->id)}}' method='POST'>
                            <input type='hidden' name='_method' value='DELETE'>
                            {{csrf_field()}}
                            <button type='submit' class='btn btn-link link_red'>DELETE</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class='row py-3'>
        <div class='col'>
        @if (count($products) > 0)
            @if (!Auth::guest() && Auth::user()->isClient() && Session::has('current_order'))
                <form action="{{action('OrderProductsController@store')}}" method='POST'>
                    <table class='table table-responsive-md table_main'>
                        <tr><th></th><th>{{ __('main.code') }}</th><th>{{ __('main.name') }}</th><th>{{ __('main.unit') }}</th><th>{{ __('main.currency') }}</th><th>{{ __('main.price') }} / {{ __('main.unit') }}</th><th>{{ __('main.quantity') }}</th></tr>
                        <?php $counter = 1 ?>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{$counter++}}.</td>
                                <td>{{$product->code}}</td>
                                <td><a href="{{url('/products'.'/'.$product->id)}}">{{$product->name}}</a></td>
                                <td>{{$product->unit}}</td>
                                <td>{{$product->currency}}</td>
                                <td>{{number_format($product->price, 2, '.', '').' '.$product->currency.' / '.$product->unit}}</td>
                                <td><input type='number' min='0' step='any' onfocus="this.value=''" value='{{$product->quantity}}' name='{{$product->id}}' class='form-control'></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td><td></td><td></td><td></td><td></td><td></td>
                            <td class='text-center'>
                                {{csrf_field()}}
                                <button type='submit' class='btn btn-primary text-uppercase'>{{ __('main.save_selections') }}</button>
                            </td>
                        </tr>
                    </table>
                </form>
            @else
                <table class='table table-responsive-md table_main'>
                    <tr><th></th><th>{{ __('main.code') }}</th><th>{{ __('main.name') }}</th><th>{{ __('main.unit') }}</th><th>{{ __('main.currency') }}</th><th>{{ __('main.price') }} / {{ __('main.unit') }}</th></tr>
                    <?php $counter = 1 ?>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{$counter++}}.</td>
                            <td>{{$product->code}}</td>
                            <td><a href="{{url('/products'.'/'.$product->id)}}">{{$product->name}}</a></td>
                            <td>{{$product->unit}}</td>
                            <td>{{$product->currency}}</td>
                            <td>{{number_format($product->price, 2, '.', '').' '.$product->currency.' / '.$product->unit}}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @else
            <h2 class='pt-5'>{{ __('main.no_results') }}</h2>
            <a href='{{url('/')}}' class='btn btn-link pt-4 text-uppercase link_main'>{{ __('main.go_to_main_page') }}</a>
        @endif
        </div>
    </div>
</div>
@endsection