@extends('layouts.app')

@section('content')

<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>{{$product->code}} - {{$usage->name}}</h1>
            <form action='{{ action('App\Http\Controllers\ProductsController@updateParameters', [$product->id, $usage->id])}}' method='POST'>
                <div class='my-5'>
                    @foreach ($parameters as $parameter)
                        <div class='form-group col-md-4 offset-md-4 align-self-center'>
                            <label>{{$parameter->name}}</label>
                            <input type='text' value='{{$parameter->productValue}}' name='{{$parameter->id}}' class='form-control'>
                        </div>
                    @endforeach
                </div>
                <input type='hidden' name='_method' value='PUT'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE PARAMETERS</button>
            </form>
        </div>
    </div>
</div>
@endsection