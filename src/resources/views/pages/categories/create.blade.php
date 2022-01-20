@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>ADD CATEGORY</h1>
            <form action='{{ action('CategoriesController@store')}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Code</label>
                    <input type='text' value='' name='code' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Category name EN</label>
                    <input type='text' value='' name='name' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Category name RU</label>
                    <input type='text' value='' name='name_ru' class='form-control' required>
                </div>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE CATEGORY</button>
            </form>
        </div>
    </div>
</div>
@endsection