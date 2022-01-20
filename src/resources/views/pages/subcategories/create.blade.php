@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>ADD SUBCATEGORY</h1>
            <form action='{{ action('SubcategoriesController@store')}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Code</label>
                    <input type='text' value='' name='code' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Subategory name EN</label>
                    <input type='text' value='' name='name' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Subategory name RU</label>
                    <input type='text' value='' name='name_ru' class='form-control' required>
                </div>
                <div class='form-group col-md-4 offset-md-4 align-self-center'>
                    <label>Discount (%)</label>
                    <input type='number' step='any' min='0' max='100' value="0" name='discount' class='form-control' required>
                </div>
                <input type='hidden' value='{{$category_id}}' name='category_id'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE SUBCATEGORY</button>
            </form>
        </div>
    </div>

</div>
@endsection