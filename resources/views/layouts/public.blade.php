<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'STAPP') }}</title>


        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        <script src="../../../public/js/app.js')" defer></script>

        <!-- Styles -->
        <link href="../../../public/css/app.css')" rel="stylesheet">
        <link href="../../../public/css/custom.css')" rel="stylesheet">
        <link rel="stylesheet" href="../../../public/font-awesome/css/font-awesome.min.css">


    </head>
    <body>
        <div class="flex-center position-ref full-height">

          @yield('content')


        <script src="../../../public/js/jquery.min.js')"></script>
        <script src="../../../public/js/popper.min.js')"></script>
        <script src="../../../public/js/bootstrap.min.js')"></script>
        <script src="../../../public/js/fullclip.min.js')"></script>
    </body>
</html>
