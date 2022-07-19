<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="FIIT STU, http://fiit.stuba.sk">
    <meta name="copyright"
          content="Fakulta informatiky a informačných technológií STU v Bratislave - www.fiit.stuba.sk">
    <meta name="description"
          content="Fotosúťaž pre študentov a zamestnancov FIIT STU a ďalších záujemcov o informatiku. (Fotografie Zima/jar 2022 - prehľad tém)">
    <meta name="keywords"
          content="fotosutaz, fotky, foto, fotografie, fotografia, photo, fiit, stu, informatici, študenti, študentská, fotograf">

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
    @if(!empty(Session::get('webAdmin')) && Session::get('webAdmin')[0] == 1)
        @include('partials.webAdminNav')
    @else
        @include('partials.nav')
    @endif
</header>
<div class="nav-background"></div>
<div class="app-body">
    <main>
        @yield('content')
    </main>
</div>
@include('partials.footer')
<script src="{{ asset( 'js/app.js' ) }}"></script>
<script src="{{ asset( 'js/jquery-1.10.2.js' ) }}"></script>
<script src="{{ asset( 'js/jquery-ui.js' ) }}"></script>
<script src="{{ asset( 'js/scripts.js' )}}"></script>
@yield('scripts')
</body>
</html>
