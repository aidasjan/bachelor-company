<div class='container pb-2'>
    <form action='{{ action('App\Http\Controllers\ProductsController@search')}}' method='GET'>
        <div class='row'>
            <div class='col-md-6 offset-md-3 d-flex'>
                <input type='text' value="" name='search_query' class='form-control h-100' placeholder='{{ __('main.search_product') }} ...' required>
                <button type='submit' class='btn btn-primary text-uppercase mx-2'><i class='fas fa-search'></i></button>
            </div>
        </div>
    </form>
</div>