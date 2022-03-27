@extends('layouts.app')

@section('content')

<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>{{$usage->name}} {{__('main.parameters')}}</h1>
            <div>{{__('main.set_additional_parameters')}}</div>
            <form action='{{ action('App\Http\Controllers\RecommendationsController@show', $usage->id)}}' method='GET'>
                <div class='my-5'>
                    @foreach ($parameters as $parameter)
                        <div class='form-group col-md-4 offset-md-4 align-self-center'>
                            <label>{{$parameter->name}}</label>
                            <input type='text' value='' name='{{$parameter->id}}' class='form-control'>
                        </div>
                    @endforeach
                </div>
                <button type='submit' class='btn btn-primary text-uppercase'>{{__('main.to_search')}}</button>
            </form>
        </div>
    </div>
</div>

@endsection