<!DOCTYPE html>
<html>
    <head>
        <title>私の一行日記</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
    <body class="bg-cream">
        @include('layouts.header')
        <div class="pt-7">
            @yield('content')
        </div>
    </body>
</html>