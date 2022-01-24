@extends('layouts.app')

@section('content')

<div class='container'>
    <div class='row py-5'>
        <div class='col'>
            <h1 class='text-uppercase'>{{ __('main.client_login') }}</h1>
        </div>
    </div>
        
    <div class='row py-3'>
        <div class='col'>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('main.email') }}</label>

                    <div class="col-md-5">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" placeholder="{{ __('main.enter_email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('main.password') }}</label>

                    <div class="col-md-5">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" value="" name="password" placeholder="{{ __('main.enter_password') }}" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4 offset-md-4">

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('main.remember_me') }}
                            </label>
                            
                        </div>

                        
                    </div>
                </div>

                <div class="row mb-0 pt-3 text-center">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary text-uppercase">
                            {{ __('main.login') }}
                        </button>

                        
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class='row py-2'>
        <div class='col'>
            <button type="button" class="btn btn-link link_main" data-toggle="modal" data-target="#login_support_modal">{{ __('main.cant_log_in') }}</button>
        </div>
    </div>
</div>

<div class="modal hide fade" id="login_support_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-5">
                <h3 class='pb-4 text-uppercase'>{{ __('main.login_problems') }}</h3>
                <div class='container_grey text-left p-4 my-2'>
                    <h4>{{ __('main.forgot_password') }}</h4>
                    <span>{{ __('main.forgot_password_desc') }}</span>
                </div>
                <div class='container_lightblue text-left p-4 my-2'>
                    <h4>{{ __('main.dont_have_account') }}</h4>
                    <span>{{ __('main.dont_have_account_desc') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
                    
@endsection
