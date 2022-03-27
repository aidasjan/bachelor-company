@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>ADD PARAMETER</h1>
            <form action='{{ action('App\Http\Controllers\ParametersController@update', $parameter->id)}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Name</label>
                    <input type='text' value='{{$parameter->name}}' name='name' class='form-control' required>
                </div>
                <input type='hidden' name='_method' value='PUT'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE PARAMETER</button>
            </form>
        </div>
    </div>
    <div class='row'>
        <div class='col'>
            @include('inc.forms.delete_button', [
                'action' => 'App\Http\Controllers\ParametersController@destroy',
                'action_param' => $parameter->id,
                'button_text' => 'DELETE PARAMETER',
                'message' => '',
                'modal_header' => 'Do you want to delete this parameter?',
                'modal_message' => ''])
        </div>
    </div>

</div>
@endsection