@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row'>
        <div class='col container_grey py-5'>
            <h1 class='text-uppercase pb-3'>UPLOAD IMAGE</h1>
            <h4 class='pb-3'>Choose an image file to upload for this category</h4>
            <form action='{{ action('CategoriesController@storeImage', $category->id) }}' method='POST' enctype='multipart/form-data'>
                @include('inc.forms.file_upload', ['submit_button_text' => 'UPLOAD', 'input_name' => 'category_file'])
                {{csrf_field()}}
            </form>
        </div>
    </div>
</div>
@endsection
