@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row py-5'>
        <div class='col container_grey py-5'>
            <h1>ADD PRODUCT</h1>
            <form action='{{ action('ProductsController@store')}}' method='POST'>
                <div class='form-group col-md-6 offset-md-3 align-self-center'>
                    <label>Product code</label>
                    <input type='text' value='' name='code' class='form-control' required>
                </div>
                <div class='form-group col-md-8 offset-md-2'>
                    <label>Product name</label>
                    <input type='text' value='' name='name' class='form-control' required>
                </div>
                <div class='form-group col-md-2 offset-md-5 align-self-center'>
                    <label>Unit</label>
                    <input type='text' value='' name='unit' class='form-control' required>
                </div>
                <div class='form-group col-md-4 offset-md-4 align-self-center'>
                    <label>Currency</label>
                    <select class='form-control' name='currency'>
                        <option value='EUR'>Euro (EUR)</option>
                        <option value='USD'>US Dollar (USD)</option>
                    </select>
                </div>
                <div class='form-group col-md-4 offset-md-4 align-self-center'>
                    <label>Price</label>
                    <input type='number' step='any' value='' name='price' class='form-control' required>
                    <small>Separated by point (1.00)</small>
                </div>
                <input type='hidden' value='{{$categoryId}}' name='category_id'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>SAVE PRODUCT</button>
            </form>
        </div>
    </div>
</div>
@endsection