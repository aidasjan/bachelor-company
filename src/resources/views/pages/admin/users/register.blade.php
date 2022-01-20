@extends('layouts.app')

@section('content')
<div class='container'>

    <div class='row'>
        <div class='col container_grey py-5'>
            <h1 class='pb-3'>NEW CLIENT</h1>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control" name="email" value="" required>
                    </div>
                </div>

                <div class="form-group row mb-0 pt-3">
                    <div class="col-md-4 offset-md-4">
                        <button type="submit" class="btn btn-primary">REGISTER</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>

    @if (Session::get('init_user_email')!==null && Session::get('init_user_password')!==null)
        <div class='row'>
            <div class='col container_blue py-5 px-5'>
                <h2 class='py-3 font-weight-bold text_white'>New user has been successfully added!</h2>
                <h4 class='text-left py-3 text_white'>Send the following credentials to the new client:</h4>
                <div class='container_grey p-3'>
                    <h5 class='text-left py-1 my-0'><b>Email:</b> {{Session::get('init_user_email')}} </h5>
                    <h5 class='text-left py-1 my-0'><b>Password:</b> {{Session::get('init_user_password')}} </h5>
                </div>
                <p class='font-weight-bold text-left pt-3 text_white'>SAVE THESE CREDENTIALS OR SEND THEM TO CLIENT NOW. <br>INFORMATION ABOVE WILL BE DELETED AS SOON AS YOU REFRESH OR EXIT THIS PAGE.</p>
            </div>
        </div>
    @endif
</div>
@endsection
