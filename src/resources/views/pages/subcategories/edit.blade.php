@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>EDIT {{$subcategory->name}}</h1>
            <form action='{{ action('SubcategoriesController@update', $subcategory->id) }}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Code</label>
                    <input type='text' value="{{$subcategory->code}}" name='code' class='form-control' disabled>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Subategory name EN</label>
                    <input type='text' value="{{$subcategory->getOriginal('name')}}" name='name' class='form-control' required>
                </div>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Subategory name RU</label>
                    <input type='text' value="{{$subcategory->getOriginal('name_ru')}}" name='name_ru' class='form-control' required>
                </div>
                <div class='form-group col-md-4 offset-md-4 align-self-center'>
                    <label>Discount (%)</label>
                    <input type='number' step='any' min='0' max='100' value="{{$subcategory->discount}}" name='discount' class='form-control' required>
                </div>
                <input type='hidden' name='_method' value='PUT'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE SUBCATEGORY</button>
            </form>
        </div>
    </div>

    <div class='row'>
        <div class='col'>
            @include('inc.forms.delete_button', [
                'action' => 'SubcategoriesController@destroy',
                'action_param' => $subcategory->id,
                'button_text' => 'DELETE SUBCATEGORY',
                'message' => 'Products inside will be deleted as well',
                'modal_header' => 'Do you want to delete this subcategory?',
                'modal_message' => 'Products inside will be deleted as well. The products will be removed from orders, related files will be deleted.'])
        </div>
    </div>

</div>
@endsection