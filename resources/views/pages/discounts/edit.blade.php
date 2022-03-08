@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>{{$user->name}} - discounts</h1>
        </div>
    </div>

    <div class='row py-4 px-2 mx-1 mb-2 container_grey'>
        <div class='col-md-8 text-left'>
            <h3>Mass assign</h3>
            <p>Assign discount for all product groups with 0 values</p>
        </div>
        <div class='col-md text-right'>
            <form action='{{ action('DiscountsController@storeAll')}}' method='POST'>
                <div class='form-group'>
                    <input type='number' value='' name='discount' class='form-control' placeholder='Discount (%)'>
                </div>
                <input type='hidden' value='{{$user->id}}' name='discount_user'>
                {{csrf_field()}}
                <button type='submit' class='btn btn-primary'>ASSIGN</button>
            </form>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>

            <form action="{{action('DiscountsController@store')}}" method='POST'>
                <table class='table_main'>
                    <tr><th></th><th>CATEGORY</th><th>DISCOUNT (%)</th></tr>
                    <?php $counter = 1; ?>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{$counter++}}.</td>
                            <td>{{$category->name}}</td>
                            <td><input type='number' min='0' max='100' step='any' onfocus="this.value=''" value='{{$category->discount}}' name='{{$category->id}}' class='form-control'></td>
                        </tr>
                    @endforeach
                    <td></td><td></td>
                    <td class='text-center'>
                        <input type='hidden' name='discount_user' value='{{$user->id}}'>
                        {{csrf_field()}}
                        <button type='submit' class='btn btn-primary'>SAVE SELECTIONS</button>
                    </td>
                </table>
            </form>

        </div>
    </div>
</div>
@endsection