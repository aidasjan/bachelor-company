<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#196074">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page info -->
    <title> @if (!empty($pageName)) {{$pageName}} | @endif WMP</title>

    @if (!empty($pageDescription))
        <meta name="description" content="{{$pageDescription}}">
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('fa/css/fa.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}?<?php echo time(); ?>" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="{{url('/favicon.png')}}"/>
</head>
<body>
    <div id="app">
        
        @include('inc.ui.navbar')
        @include('inc.ui.order_tab')
        @include('inc.ui.cover')
        
        <div class='text-center pt-5' style='padding-bottom: 12rem'>
            @yield('content')
        </div>

        @include('inc.ui.footer')

        @if (session()->has('success') || $errors->any())
            @include('inc.ui.notification')
        @endif
    </div>
</body>
</html>
