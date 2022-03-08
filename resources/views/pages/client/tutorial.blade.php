@extends('layouts.app')

@section('content')
    <div class='container'>

        <div class='row py-5'>
            <div class='col'>
                <h1 class='text-uppercase'>{{ __('main.how_to_make_order') }}?</h1>
            </div>
        </div>

        <div class='row py-2 text-left'>
            <div class='col'>
                <p class='font-weight-bold pt-3 h5'><span class='h1 pr-2'>1.</span> {{ __('main.tutorial_1_1') }}</p>
                <img src="{{asset('img/tutorial/tutorial1.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-2'>{{ __('main.tutorial_1_2') }}</p>

                <p class='font-weight-bold pt-3 h5'><span class='h1 pr-2'>2.</span> {{ __('main.tutorial_2_1') }}</p>
                <img src="{{asset('img/tutorial/tutorial2.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-2'>{{ __('main.tutorial_2_2') }}</p>

                <p class='font-weight-bold pt-3 h5'><span class='h1 pr-2'>3.</span> {{ __('main.tutorial_3_1') }}</p>
                <img src="{{asset('img/tutorial/tutorial3.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-3'>{{ __('main.tutorial_3_2') }}</p>
                <img src="{{asset('img/tutorial/tutorial4.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-2'>{{ __('main.tutorial_3_3') }}</p>

                <p class='font-weight-bold pt-3 h5'><span class='h1 pr-2'>4.</span> {{ __('main.tutorial_4_1') }}</p>
                <img src="{{asset('img/tutorial/tutorial5.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-2'>{{ __('main.tutorial_4_2') }}</p>

                <p class='font-weight-bold pt-3 h5'><span class='h1 pr-2'>5.</span> {{ __('main.tutorial_5_1') }}</p>
                <img src="{{asset('img/tutorial/tutorial6.png')}}" class='image_tutorial'>
                <p class='font-weight-bold pt-2'>{{ __('main.tutorial_5_2') }}</p>
                
            </div>
        </div>

    </div>
@endsection