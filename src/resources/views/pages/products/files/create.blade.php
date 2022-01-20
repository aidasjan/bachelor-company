@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col container_grey py-5'>
                <h1>ADD NEW FILE</h1>
                <form action='{{ action('ProductFilesController@store') }}' method='POST' enctype='multipart/form-data'>
                    <input type='hidden' value='{{$product_id}}' name='product_id'>
                    <div class='form-group col-md-4 offset-md-4 align-self-center'>
                        <label>Name</label>
                        <input type='text' value='' name='name' class='form-control'>
                        <small>Will be visible for users. Not required for images.</small>
                    </div>
                    @include('inc.forms.file_upload', ['submit_button_text' => 'SAVE', 'input_name' => 'product_file'])
                    {{csrf_field()}}
                </form>
            </div>
        </div>
    </div>
@endsection