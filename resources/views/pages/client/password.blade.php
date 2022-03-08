@extends('layouts.app')

@section('content')
    <div class='container'>
        <div class='row py-5'>
            <div class='col'>
                @if (!Auth::guest() && Auth::user()->isNewClient())
                    <h1>{{ __('main.welcome') }}, {{auth()->user()->name}}</h1>
                    <h4 class='pb-2'>{{ __('main.welcome_change_password') }}</h4>
                @elseif (!Auth::guest() && (Auth::user()->isClient() || Auth::user()->isAdmin()))
                    <h1 class='text-uppercase'>{{ __('main.change_password') }}</h1>
                @endif
            </div>
        </div>

        <div class='row py-5 container_grey'>
            <div class='col'>
                <p class='pt-2 pb-4'>{{ __('main.password_desc') }}</p>

                <form action="{{ action('App\Http\Controllers\UsersController@passwordChange') }}" method='POST'>
                    
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('main.password') }}</label>
                        <div class="col-md-5">
                            <input id="password" type="password" class="form-control" name="password" value="" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('main.confirm_password') }}</label>
                        <div class="col-md-5">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="" required>
                        </div>
                    </div>

                    @if (!Auth::guest() && Auth::user()->isNewClient())
                        <div class='row pt-4 pb-2'>
                            <div class='col'>
                                <p class='font-weight-bold'>{{ __('main.privacy_consent_1') }} <a href="{{url('/privacy-policy')}}" target="_blank" class='link_main'>{{ __('main.privacy_policy') }}</a>. {{ __('main.privacy_consent_2') }}</p>
                            </div>
                        </div>
                    @endif

                    {{csrf_field()}}

                    <div class="form-group row mb-0">
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" class="btn btn-primary text-uppercase">
                                @if (!Auth::guest() && Auth::user()->isNewClient())
                                    {{ __('main.start') }}
                                @elseif (!Auth::guest() && (Auth::user()->isClient() || Auth::user()->isAdmin()))
                                    {{ __('main.change') }}
                                @endif
                            </button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection