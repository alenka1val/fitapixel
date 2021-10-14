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
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/4380ff5b6c.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stylesheet.css') }}" rel="stylesheet">
</head>
<body>
<header class="app-header">
    @include('partials.nav')
</header>
<div class="nav-background"></div>
<div class="app-body">
    <main>
        <div>
            @yield('content')
            <div id="myModal" class="modal">
        <span class="close"><i class="fa fa-times zoomImageI"></i></span>
        <img class="modal-content" id="img01">
        <div id="caption">
            <i class="fa fa-arrow-left zoomImageArrows" onclick="moveLeft({{ $photoList }})"></i>
            <div class="modal-div">
                <p id="captionText1"></p>
                <p id="captionText2"></p>
                <p id="captionText3"></p>
            </div>
            <i class="fa fa-arrow-right zoomImageArrows" onclick="moveRight({{ $photoList }})"></i>
        </div>
    </div>
        </div>
    </main>
</div>
@include('partials.footer')
<script src="{{asset( '/js/scripts.js' )}}"></script>
@yield('scripts')
</body>
</html>
