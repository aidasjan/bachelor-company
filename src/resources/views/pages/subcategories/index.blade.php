@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col'>
                <h1 style='text-transform: uppercase;'>{{$category->name}}</h1>
            </div>
        </div>

        @if (!Auth::guest() && Auth::user()->isAdmin())
            <div class='row py-3 container_grey'>
                <div class='col'>
                    <a href="{{url('/categories'.'/'.$category->id.'/edit')}}" class='btn btn-link link_main'>EDIT CATEGORY</a>
                    <a href="{{url('/subcategories/create/'.$category->id)}}" class='btn btn-link link_main'>ADD SUBCATEGORY</a>
                    <a href="{{url('/reorder/subcategories/'.$category->id)}}" class='btn btn-link link_main'>REORDER</a>
                </div>
            </div>
        @endif

        <div class='menu_section_middle'>
            <div class='container'>
            <?php $counter=0; ?>
            @foreach ($subcategories as $subcategory)
            @if ($counter % 2 == 0)
                <div class='row'>
                    <div class='col-md py-2 px-3'>
                        <a href="{{url($subcategory->getDisplayUrl())}}"><div class='button_big button_big_inv py-4 px-2'><div>{{$subcategory->name}}</div></div></a>
                    </div>
            @endif
            @if ($counter % 2 != 0)
                    <div class='col-md py-2 px-3'>
                        <a href="{{url($subcategory->getDisplayUrl())}}"><div class='button_big button_big_inv py-4 px-2'><div>{{$subcategory->name}}</div></div></a>
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