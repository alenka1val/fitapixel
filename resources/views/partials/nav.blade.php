<div class="pos-f-t">
    <nav class="navbar fiitapixel_nav">
        <div class="nav_grid left_grid large_grid">
            <h1>
                <img id="nav_img" src="{{asset( '/images/fiitapixel_fulltext_blue.png' )}}" title="domov"
                     alt="FIITaPIXEL" onclick="home()">
                {{--                <a class="nav-home" href="{{ route('home') }}">--}}
                {{--                    FiitAPixel--}}
                {{--                </a>--}}
            </h1>
        </div>
        <div class="nav_icon"></div>
        <div class="nav_grid right_grid large_grid">
            <div>
            </div>
            <a class="nav-link @yield('competitionActive')" href="{{ route('info.competition') }}">
                Súťaže
            </a>
            <a class="nav-link @yield('juryActive')" href="{{ route('info.judges') }}">
                Porotcovia
            </a>
            <a class="nav-link @yield('galleryActive')" href="{{ route('info.gallery') }}">
                Galéria
            </a>
            @if(!is_null(Auth::user()) && Session::get('role')[0] != 'jury')
                <a class="nav-link @yield('addActive')" href="{{ route('photographies.create') }}">
                    Pridať foto
                </a>
            @endif
            @if(!is_null(Auth::user()) && (Session::get('role')[0] == 'jury' || Session::get('role')[0] == 'admin'))
                <a class="nav-link @yield('voteActive')" href="{{ route('info.voteList') }}">
                    Vyhodnotiť
                </a>
            @endif
            @guest
                <a class="nav-link" href="{{ route('login') }}">
                    <!-- <i class="fa-solid fa-user"></i> -->
                    Prihlásenie
                </a>
                {{--                <a class="nav-link" href="{{ route('register') }}">--}}
                {{--                    <!-- <i id="icon" class="fas fa-sign-in-alt "></i> -->--}}
                {{--                    Register--}}
                {{--                </a>--}}
            @endguest
            @auth
                {{--                <form id="profile" action="" method="get">--}}
                {{--                    @csrf--}}
                {{--                    <a class="nav-link" href="javascript:{}" onclick="document.getElementById('profile').submit();">--}}
                {{--                        <!-- <i class="fas fa-user-circle"></i> -->--}}
                {{--                        Profile--}}
                {{--                    </a>--}}
                {{--                </form>--}}
                {{--                <form id="logout_form" action="{{ route('logout') }}" method="POST">--}}
                {{--                    @csrf--}}
                {{--                    <a class="nav-link" href="javascript:{}" onclick="document.getElementById('logout_form').submit();">--}}
                {{--                        <!-- <i class="fas fa-sign-out-alt"></i> -->--}}
                {{--                        Log out--}}
                {{--                    </a>--}}
                {{--                </form>--}}

                <a class="nav-link @yield('profileActive')" href="{{ route('users.profile') }}">
                    <!-- <i class="fa-solid fa-user"></i> -->
                    Môj profil
                </a>
            @endauth
        </div>
        <div>
            <a class="nav_icon" onclick="collapseNav()">
                <i id="burgerMenu" class="fa fa-bars"></i>
                <i id="burgerX" class="fa fa-times"></i>
            </a>
        </div>
    </nav>
    <section id="collapse">
        <div id="collapse-items">
            <p class="p-nav"><a class="collapse_item @yield('competitionActive')"
                                href="{{ route('info.competition') }}">Súťaže</a></p>
            <p class="p-nav"><a class="collapse_item @yield('juryActive')"
                                href="{{ route('info.judges') }}">Porotcovia</a></p>
            <p class="p-nav"><a class="collapse_item @yield('galleryActive')"
                                href="{{ route('info.gallery') }}">Galéria</a></p>
            @if(!is_null(Auth::user()) && Session::get('role')[0] != 'jury')
                <p class="p-nav"><a class="collapse_item @yield('addActive')"
                                    href="{{ route('photographies.create') }}">Vyhodnotiť</a></p>
            @endif
            @if(!is_null(Auth::user()) && (Session::get('role')[0] == 'jury' || Session::get('role')[0] == 'admin'))
                <p class="p-nav"><a class="collapse_item @yield('voteActive')" href="{{ route('info.voteList') }}">Vyhodnotiť</a>
                </p>
            @endif
            @guest
                <p class="p-nav">
                    <a class="collapse_item" href="{{ route('login') }}">
                        Prihlásenie
                    </a>
                </p>
                {{--            <p><a class="collapse_item" href="{{ route('register') }}">--}}
                {{--                    Register--}}
                {{--                </a></p>--}}
            @endguest
            @auth
                {{--            <p>--}}
                {{--            <form id="profile" action="" method="get">--}}
                {{--                @csrf--}}
                {{--                <a class="collapse_item" href="javascript:{}"--}}
                {{--                   onclick="document.getElementById('profile').submit();">--}}
                {{--                    Profile--}}
                {{--                </a>--}}
                {{--            </form>--}}
                {{--            </p>--}}
                {{--                <p class="p-nav">--}}
                {{--                <form id="logout_form" action="{{ route('logout') }}" method="POST">--}}
                {{--                    @csrf--}}
                {{--                    <a class="collapse_item" href="javascript:{}"--}}
                {{--                       onclick="document.getElementById('logout_form').submit();">--}}
                {{--                        Log out--}}
                {{--                    </a>--}}
                {{--                </form>--}}
                {{--                </p>--}}
                <p class="p-nav"><a class="nav-link @yield('profileActive')" href="{{ route('users.profile') }}">Môj
                        profil</a>
                </p>
            @endauth
        </div>

    </section>
</div>
