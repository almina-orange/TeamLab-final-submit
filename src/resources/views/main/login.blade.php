<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{-- CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
<div class="container jumbotron">
    <h1 class="display-4">Hello, Hinstagram!</h1>
    <hr class="my-4">
    <a class="btn btn-primary btn-lg" href="/login/github" role="button">GitHub Login</a>
</div>

{{-- JavaScript --}}
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>