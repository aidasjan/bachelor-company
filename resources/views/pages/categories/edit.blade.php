@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>EDIT {{$category->name}}</h1>
            <form action='{{ action('CategoriesController@update', $category->id)}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Code</label>
                    <input type='text' value="{{$category->code}}" name='code' class='form-control' disabled>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Category name EN</label>
                    <input type='text' value="{{$category->getOriginal('name')}}" name='name' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Category name RU</label>
                    <input type='text' value="{{$category->getOriginal('name_ru')}}" name='name_ru' class='form-control' required>
                </div>
                <div class='form-group col-md-4 offset-md-4 align-self-center'>
                    <label>Discount (%)</label>
                    <input type='number' step='any' min='0' max='100' value="{{$category->discount}}" name='discount' class='form-control' required>
                </div>
                <input type='hidden' name='_method' value='PUT'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE CATEGORY</button>
            </form>
        </div>
    </div>
    <div class='row'>
        <div class='col'>
            @include('inc.forms.delete_button', [
                'action' => 'CategoriesController@destroy',
                'action_param' => $category->id,
                'button_text' => 'DELETE CATEGORY',
                'message' => 'Subcategories and products inside will be deleted as well',
                'modal_header' => 'Do you want to delete this category?',
                'modal_message' => 'Subcategories and products inside will be deleted as well. The products will be removed from orders, related files will be deleted.'])
        </div>
    </div>

</div>
@endsection