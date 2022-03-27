@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>ADD PARAMETER</h1>
            <form action='{{ action('App\Http\Controllers\ParametersController@store')}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Name</label>
                    <input type='text' value='' name='name' class='form-control' required>
                </div>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE PARAMETER</button>
            </form>
        </div>
    </div>
</div>
@endsection