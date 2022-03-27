@extends('layouts.app')

@section('content')

<div class='container text-center'>

    <div class='row pt-5 pb-4'>
        <div class='col'>
            <h1 class='text-uppercase'>Usages</h1>
            <div class='mt-4'>
                <a href='{{url('/usages/create')}}' class='btn btn-primary'>ADD NEW</a>
            </div>
        </div>
    </div>

    <div class='row py-3'>
        <div class='col-md-10 offset-md-1'>
            <table class='table table_main'>
                <tr><th>USAGE</th><th></th></tr>
                @foreach ($usages as $usage)
                    <tr>
                        <td>{{$usage->name}}</td>
                        <td><a href="{{url('/usages/'.$usage->id.'/edit')}}" class='text-uppercase'>EDIT</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection