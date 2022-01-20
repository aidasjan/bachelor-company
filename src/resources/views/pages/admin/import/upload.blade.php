@extends('layouts.app')

@section('content')
<div class='container'>
    <div class='row'>
        <div class='col container_grey py-5'>
            <h1 class='text-uppercase pb-3'>IMPORT {{$type}}</h1>
            <h4 class='pb-3'>Choose XLSX file to upload {{$type}}</h4>
            <form action='{{ action('ImportController@importFromFile', $type) }}' method='POST' enctype='multipart/form-data'>
                @include('inc.forms.file_upload', ['submit_button_text' => 'IMPORT', 'input_name' => 'import_file'])
                {{csrf_field()}}
            </form>
        </div>
    </div>
</div>
@endsection
