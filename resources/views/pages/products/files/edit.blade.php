@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col container_grey py-5'>
                <h1>EDIT FILE</h1>
                <form action='{{ action('App\Http\Controllers\ProductFilesController@update', $productFile->id) }}' method='POST'>
                    <input type='hidden' name='_method' value='PUT'>
                    <div class='form-group col-md-4 offset-md-4 align-self-center'>
                        <label>Name</label>
                        <input type='text' value='{{$productFile->name}}' name='name' class='form-control'>
                        <small>Will be visible for users. Not required for images.</small>
                    </div>
                    {{csrf_field()}}
                    <button type='submit' class='btn btn-primary'>SAVE FILE</button>
                </form>
            </div>
        </div>

        <div class='row py-2'>
            <div class='col'>
                <a href="{{url('/files/documents/'.$productFile->id)}}" target="_blank" class='link_main'>VIEW FILE</a>
            </div>
        </div>
        <div class='row pb-3'>
            <div class='col'>
                <form action='{{ action('App\Http\Controllers\ProductFilesController@update', $productFile->id)}}' method='POST'>
                    <input type='hidden' name='_method' value='DELETE'>
                    {{csrf_field()}}
                    <button type='submit' class='btn btn-link link_red' href='#'>DELETE FILE</button>
                </form>
            </div>
        </div>
    </div>
@endsection