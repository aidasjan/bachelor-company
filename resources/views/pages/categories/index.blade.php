@extends('layouts.app')

@section('content')
<div class='container'>

    <div class='row pt-3 pb-4'>
        <div class='col'>
            <h1 class='text-uppercase'>{{__('main.browse_products')}}</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>
            @include('inc.forms.search_bar')
        </div>
    </div>

    <hr>

    @if (!Auth::guest() && Auth::user()->isAdmin())
        <div class='row py-3 container_grey'>
            <div class='col'>
                <a href='{{url('/categories/create')}}' class='btn btn-link link_main'>ADD CATEGORY</a>
                <a href='{{url('/reorder/categories')}}' class='btn btn-link link_main'>REORDER</a>
            </div>
        </div>
    @endif

    <div class='row py-3'>
        <div class='col'>
            <?php $counter=0; ?>
            @foreach ($categories as $category)
                @if ($counter % 2 == 0)
                    <div class='row'>
                        <div class='col-md py-2 px-3'>
                            <a href='{{url($category->getDisplayUrl())}}'><div class='button_big py-4 px-2'><div>{{$category->name}}</div></div></a>
                        </div>
                @endif
                @if ($counter % 2 != 0)
                        <div class='col-md py-2 px-3'>
                            <a href='{{url($category->getDisplayUrl())}}'><div class='button_big py-4 px-2'><div>{{$category->name}}</div></div></a>
                        </div>
                    </div>
                @endif
                <?php $counter++; ?>
            @endforeach
            @if ($counter % 2 != 0) </div> @endif
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>
            @include('inc.ui.personal_account_banner')
        </div>
    </div>

    <hr>

    <div class='row container_lightblue py-5 my-4'>
        <div class='col'>
            <div class='col-md-6 offset-md-3'>
                <div class='text-uppercase mb-4'><h2>GET RECOMMENDATIONS</h2></div>
                <div class='mb-4'>What is the scope of your problem?</div>
                @include('inc.forms.usages', ['action' => action('App\Http\Controllers\RecommendationsController@showParameters'), 'buttonText' => 'SEE THE OPTIONS'])
            </div>
        </div>
    </div>

    @if (count($discountCategories) > 0)
        <div class='row py-5'>
            <div class='col'>
                <h2 class='text-uppercase mb-5'>{{ __('main.current_discounts') }}</h2>
                <table class='table table_main h5'>
                    <?php $counter = 1 ?>
                    @foreach ($discountCategories as $category)
                        <tr>
                            <td>{{$counter++}}.</td>
                            <td><a href="{{url($category->getDisplayUrl())}}" class='text-uppercase'>{{$category->name}}</a></td>
                            <td class='font-weight-bold py-4'>{{$category->discount}}%</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif

</div>
@endsection