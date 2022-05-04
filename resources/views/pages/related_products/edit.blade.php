@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>RELATED PRODUCTS - {{$product->name}}</h1>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col'>

            <form action="{{action('App\Http\Controllers\RelatedProductsController@store')}}" method='POST'>
                <table class='table_main'>
                    <tr><th></th><th>CATEGORY</th><th>CODE</th><th>NAME</th><th>RELATED</th></tr>
                    <?php $counter = 1; ?>
                    @foreach ($products as $prod)
                        <tr>
                            <td>{{$counter++}}.</td>
                            <td>{{$prod->category->name}}</td>
                            <td>{{$prod->code}}</td>
                            <td>{{$prod->name}}</td>
                            <td><input type="checkbox" name="{{$prod->id}}" @if ($relatedProducts->contains('related_product_id', $prod->id)) checked @endif></td>
                        </tr>
                    @endforeach
                    <td></td><td></td><td></td><td></td>
                    <td class='text-center'>
                        <input type='hidden' name='product' value='{{$product->id}}'>
                        {{csrf_field()}}
                        <button type='submit' class='btn btn-primary'>SAVE</button>
                    </td>
                </table>
            </form>

        </div>
    </div>
</div>
@endsection