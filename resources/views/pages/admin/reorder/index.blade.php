@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>Reorder {{$type}}</h1>        
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>
            <form action='{{ $parent_id != null ? action('App\Http\Controllers\ReorderController@reorder', [$type, $parent_id]) : action('App\Http\Controllers\ReorderController@reorderRoot', $type) }}' method='POST'>
                {{ csrf_field() }}
                <div class='text-right'>
                    <button type='submit' class='btn btn-primary text-uppercase my-3'>Save Selections</button>
                </div>
                <div id='sortable_list'>
                    @foreach ($items as $item)
                        <div class='text-left container_grey px-3 py-1 my-1'>
                            <input type='hidden' class='sortable_item' name='{{$item->id}}' value=''>
                            <div class='row'>
                                <div class='col'><b>{{$item->code}}</b> | {{$item->name}}</div>
                                <div class='col-2 text-right'><i class='fas fa-bars'></i></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

</div>

<script src="{{ asset('js/jquery/jquery-ui.js') }}"></script>
<script src="{{ asset('js/scripts/sortable.js') }}"></script>

@endsection