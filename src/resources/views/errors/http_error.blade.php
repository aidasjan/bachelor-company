<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#196074">

        <title> Error | WMP </title>
        
        <script src="{{ asset('js/bootstrap/bootstrap.js') }}"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}?<?php echo time(); ?>" rel="stylesheet">
    </head>
    <body>
        <div id='app'>
            <div class='text-center pt-5' style='padding-bottom: 12rem'>
                <div class='container'>
                    <h1 class='display-1'>{{$error_code}}</h1>
                    <h3>{{$error_message}}</h3>
                    <p class='py-2'>{{$additional_message}}</p>
                    <div class='py-3'>
                        <a href='{{url('/')}}' class='btn btn-primary text-uppercase'>Go to main page</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
