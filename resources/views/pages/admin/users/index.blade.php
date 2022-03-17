@extends('layouts.app')

@section('content')
    <div class='container'>

        <div class='row py-5'>
            <div class='col'>
            <h1>USERS</h1>
            </div>
        </div>

        <div class='row py-3'>
            <div class='col'>
                <table class='table table-responsive-md table_main'>
                    <tr><th></th><th>NAME</th><th>EMAIL</th><th></th></tr>
                    <?php $counter = 1 ?>
                    @foreach ($users as $user)
                        @if ($user->isClient() || $user->isNewClient())
                            <tr>
                                <td>{{$counter++}}.</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td><a href="{{url('discounts/'.$user->id.'/edit')}}" class='link_main'>DISCOUNTS</a></td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
        
    </div>
@endsection