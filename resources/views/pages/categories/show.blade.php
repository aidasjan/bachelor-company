@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col'>
                <h1 class='text-uppercase'>{{$category->name}}</h1>
            </div>
        </div>

        @if (!Auth::guest() && Auth::user()->isAdmin())
            <div class='row py-3 container_grey'>
                <div class='col'>
                    <a href="{{url('/categories'.'/'.$category->id.'/edit')}}" class='btn btn-link link_main'>EDIT CATEGORY</a>
                    @if (count($childCategories) === 0) <a href="{{url('/products/create/'.$category->id)}}" class='btn btn-link link_main'>ADD PRODUCT</a> @endif
                    <a href="{{url('/categories/create/'.$category->id)}}" class='btn btn-link link_main'>ADD SUBCATEGORY</a>
                    <a href="{{url('/reorder/categories/'.$category->id.'?redirectUrl='.$category->getDisplayUrl())}}" class='btn btn-link link_main'>REORDER</a>
                </div>
            </div>
        @endif

        <div class='menu_section_middle'>
            <div class='container'>
            <?php $counter=0; ?>
            @foreach ($childCategories as $childCategory)
            @if ($counter % 2 == 0)
                <div class='row'>
                    <div class='col-md py-2 px-3'>
                        <a href="{{url($childCategory->getDisplayUrl())}}"><div class='button_big button_big_inv py-4 px-2'><div>{{$childCategory->name}}</div></div></a>
                    </div>
            @endif
            @if ($counter % 2 != 0)
                    <div class='col-md py-2 px-3'>
                        <a href="{{url($childCategory->getDisplayUrl())}}"><div class='button_big button_big_inv py-4 px-2'><div>{{$childCategory->name}}</div></div></a>
                    </div>
                </div>
            @endif
                <?php $counter++; ?>
            @endforeach
            @if ($counter % 2 != 0) <div class='col-md'></div></div> @endif
            </div>
        </div>
    </div>
@endsection