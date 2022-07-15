<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" href='{{ asset( '/images/fiitapixel_blue.ico' ) }}' type="image/x-icon">

    <!-- Scripts -->
    <link href=“https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css” rel=“stylesheet”>
    <script src="{{ asset('js/select.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/4380ff5b6c.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/slider.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/slider.css') }}" rel="stylesheet">
</head>
<body>
<header class="app-header">
    @include('partials.nav')
</header>
<div class="nav-background"></div>
<div class="app-body">
    <main>
        @yield('content')
    </main>
</div>
@include('partials.footer')
<script src="{{ asset( 'js/app.js' ) }}"></script>
<script src="{{ asset( '/js/scripts.js' )}}"></script>
<script src="{{ asset( 'js/jquery-1.10.2.js' ) }}"></script>
<script src="{{ asset( 'js/jquery-ui.js' ) }}"></script>
@yield('scripts')
</body>
</html>
