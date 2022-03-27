@extends('layouts.app')

@section('content')
    <div class='container'>

        <div class='row pt-1'>
            @if (count($images) > 0)
            <div class='col-md pt-3'>
                    <div id="carouselProductImages" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                @if (!Auth::guest() && Auth::user()->isAdmin()) <a href="{{url('/product-files'.'/'.$images->first()->id.'/edit')}}"> @endif
                                <img class="mw-100 mh-100" src="{{url('/files/images/'.$images->first()->id)}}">
                                @if (!Auth::guest() && Auth::user()->isAdmin()) </a> @endif
                            </div>
                            @foreach ($images->slice(1) as $image)
                                <div class="carousel-item">
                                    @if (!Auth::guest() && Auth::user()->isAdmin()) <a href="{{url('/product-files'.'/'.$image->id.'/edit')}}"> @endif
                                    <img class="mw-100 mh-100" src="{{url('/files/images/'.$image->id)}}">
                                    @if (!Auth::guest() && Auth::user()->isAdmin()) </a> @endif
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselProductImages" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselProductImages" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
            </div>

            <div class='col-md text-left pt-3'>
            @else
            <div class='col-md pt-3'>
            @endif
                <div class='row pt-4 pb-2'>
                    <div class='col-md'>
                        <h4>{{$product->code}}</h4>
                    </div>
                </div>
                <div class='row py-1'>
                    <div class='col-md'>
                        <h1>{{$product->name}}</h1>
                    </div>
                </div>
                <div class='row pb-2'>
                    <div class='col-md'>
                        <h3 class='text_main'>{{number_format($product->price, 2, '.', '').' '.$product->currency.' / '.$product->unit}}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class='row form_box mt-5'>
            <div class='col-md'>
                <div class='container'>
                    <div class='row py-2'>
                        <div class='col-md text-uppercase'><h2>{{ __('main.product_info') }}</h2></div>
                    </div>

                    @if (count($documents) > 0)
                        <?php $counter=0; ?>
                        @foreach ($documents as $file)
                            @if ($counter % 2 == 0)
                                <div class='row'>
                                    <div class='col-md py-2 px-3'>
                                        @if (!Auth::guest() && Auth::user()->isAdmin())
                                            <a href="{{url('/product-files'.'/'.$file->id.'/edit')}}"><div class='button_big py-4 px-2'>{{$file->name}}</div></a>
                                        @else
                                            <a href="{{url('/files/documents/'.$file->id)}}" target="_blank"><div class='button_big py-4 px-2'>{{$file->name}}</div></a>
                                        @endif
                                    </div>
                            @endif
                            @if ($counter % 2 != 0)
                                    <div class='col-md py-2 px-3'>
                                        @if (!Auth::guest() && Auth::user()->isAdmin())
                                            <a href="{{url('/product-files'.'/'.$file->id.'/edit')}}"><div class='button_big py-4 px-2'>{{$file->name}}</div></a>
                                        @else
                                            <a href="{{url('/files/documents/'.$file->id)}}" target="_blank"><div class='button_big py-4 px-2'>{{$file->name}}</div></a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <?php $counter++; ?>
                        @endforeach
                        @if ($counter % 2 != 0) </div> @endif
                    @else
                        <div class='row pt-3'>
                            <div class='col-md'>
                                <h5>{{ __('main.product_no_info') }}</h5>
                            </div>
                        </div>
                    @endif
                    
                    <div class='row py-3'>
                        <div class='col-md'>
                            <span>{{ __('main.product_more_info') }}<b>{{config('custom.company_info.email')}}</b></span>
                        </div>
                    </div>
                    

                    @if (!Auth::guest() && Auth::user()->isAdmin())
                        <a href="{{url('/product-files/create/'.$product->id)}}"><div class='btn btn-primary'>ADD FILE</div></a>
                    @endif
                </div>
            </div>
        </div>

        @if (!(count($relatedProducts) == 0 && (Auth::guest() || (!Auth::guest() && Auth::user()->isClient() && !Session::has('current_order')))))
        <div class='row container_lightblue py-5 my-4'>
            <div class='col'>
                
                @if (!Auth::guest() && Auth::user()->isClient() && Session::has('current_order'))
                    <h2 class='pt-2 pb-4 text-uppercase'>{{ __('main.add_to_order') }}</h2>
                    <form action="{{action('App\Http\Controllers\OrdersController@storeOrderProducts')}}" method='POST'>
                        <table class='table table-responsive-md table_main mb-3'>
                            <tr><th></th><th>{{ __('main.code') }}</th><th>{{ __('main.name') }}</th><th>{{ __('main.unit') }}</th><th>{{ __('main.currency') }}</th><th>{{ __('main.price') }} / {{ __('main.unit') }}</th><th>{{ __('main.quantity') }}</th></tr>
                            <?php $counter = 1 ?>
                            <tr class="container_white">
                                <td>{{$counter++}}.</td>
                                <td>{{$product->code}}</td>
                                <td><a href="{{url('/products'.'/'.$product->id)}}">{{$product->name}}</a></td>
                                <td>{{$product->unit}}</td>
                                <td>{{$product->currency}}</td>
                                <td>{{number_format($product->price, 2, '.', '').' '.$product->currency.' / '.$product->unit}}</td>
                                <td><input type='number' min='0' step='any' onfocus="this.value=''" value='{{$product->quantity}}' name='{{$product->id}}' class='form-control'></td>
                            </tr>
                            @foreach ($relatedProducts as $related_product)
                                <tr class='container_grey'>
                                    <td>{{$counter++}}.</td>
                                    <td>{{$related_product->code}}</td>
                                    <td><a href="{{url('/products'.'/'.$related_product->id)}}">{{$related_product->name}}</a></td>
                                    <td>{{$related_product->unit}}</td>
                                    <td>{{$related_product->currency}}</td>
                                    <td>{{number_format($related_product->price, 2, '.', '').' '.$related_product->currency.' / '.$related_product->unit}}</td>
                                    <td><input type='number' min='0' step='any' onfocus="this.value=''" value='{{$related_product->quantity}}' name='{{$related_product->id}}' class='form-control'></td>
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

                @elseif (count($relatedProducts) > 0)
                    <h2 class='pt-2 pb-4 text-uppercase'>{{ __('main.related_products') }}</h2>
                    <table class='table table-responsive-md table_main mb-3'>
                        <tr><th></th><th>{{ __('main.code') }}</th><th>{{ __('main.name') }}</th><th>{{ __('main.unit') }}</th><th>{{ __('main.currency') }}</th><th>{{ __('main.price') }} / {{ __('main.unit') }}</th></tr>
                        <?php $counter = 1 ?>
                        @foreach ($relatedProducts as $related_product)
                            <tr>
                                <td>{{$counter++}}.</td>
                                <td>{{$related_product->code}}</td>
                                <td><a href="{{url('/products'.'/'.$related_product->id)}}">{{$related_product->name}}</a></td>
                                <td>{{$related_product->unit}}</td>
                                <td>{{$related_product->currency}}</td>
                                <td>{{number_format($related_product->price, 2, '.', '').' '.$related_product->currency.' / '.$related_product->unit}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                @if (!Auth::guest() && Auth::user()->isAdmin())
                    <a href="{{url('/related-products'.'/'.$product->id.'/edit')}}"><div class='btn btn-primary'>EDIT RELATED PRODUCTS</div></a>
                @endif
            </div>
        </div>
        @endif

        @if (!Auth::guest() && Auth::user()->isAdmin())
            <div class='row container_lightblue'>
                <div class='col-md-6 offset-md-3 py-5'>
                    <div class='text-uppercase mb-4'><h2>EDIT PARAMETERS</h2></div>
                    @include('inc.forms.usages', ['action' => action('App\Http\Controllers\ProductsController@editParameters', $product->id), 'buttonText' => 'EDIT'])
                </div>
            </div>
        @endif

        <div class='row'>
            <div class='col-md'>
                @if (!Auth::guest() && Auth::user()->isAdmin())
                    <div class='mt-3'><a href="{{url('/products'.'/'.$product->id.'/edit')}}" class='link_main'>EDIT THIS PRODUCT</a></div>
                @endif
            </div>
        </div>
    </div>
@endsection