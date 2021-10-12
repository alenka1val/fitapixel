<div class="pos-f-t">
    <nav class="navbar navbar-dark bg-dark fiitapixel_nav">
        <div class="nav_grid left_grid large_grid">
            <a class="nav-link" href="{{ route('home') }}">
                Ročníky
            </a>
            <a class="nav-link" href="{{ route('home') }}">
                Výsledky
            </a>
            <a class="nav-link" href="{{ route('home') }}">
                Pravidlá
            </a>
        </div>
        <div class="nav_grid middle_grid">
            <img id="nav_img" src="{{asset( '/images/fiitapixel_fulltext_blue.png' )}}" title="domov"
                 alt="FIITAPIXEL" onclick="home()">
        </div>
        <div class="nav_icon"></div>
        <div class="nav_grid right_grid large_grid">
            <div>
            </div>
            @guest
                <a class="nav-link" href="{{ route('login') }}">
                    <!-- <i class="fa-solid fa-user"></i> -->
                    Log in
                </a>
                <a class="nav-link" href="{{ route('register') }}">
                    <!-- <i id="icon" class="fas fa-sign-in-alt "></i> -->
                    Register
                </a>
            @endguest
            @auth
                <form id="profile" action="" method="get">
                    @csrf
                    <a class="nav-link" href="javascript:{}" onclick="document.getElementById('profile').submit();">
                        <!-- <i class="fas fa-user-circle"></i> -->
                        Profile
                    </a>
                </form>
                <form id="logout_form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a class="nav-link" href="javascript:{}" onclick="document.getElementById('logout_form').submit();">
                        <!-- <i class="fas fa-sign-out-alt"></i> -->
                        Log out
                    </a>
                </form>
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
        <p><a class="collapse_item @yield('homeActive')" href="{{ route('home') }}">Ročníky</a></p>
        <p><a class="collapse_item @yield('homeActive')" href="{{ route('home') }}">Výsledky</a></p>
        <p><a class="collapse_item @yield('homeActive')" href="{{ route('home') }}">Pravidlá</a></p>
        @guest
            <p><a class="collapse_item @yield('homeActive')" href="{{ route('login') }}">
                    Log in
                </a></p>
            <p><a class="collapse_item @yield('homeActive')" href="{{ route('register') }}">
                    Register
                </a></p>
        @endguest
        @auth
            <p>
            <form id="profile" action="" method="get">
                @csrf
                <a class="collapse_item @yield('homeActive')" href="javascript:{}"
                   onclick="document.getElementById('profile').submit();">
                    Profile
                </a>
            </form>
            </p>
            <p>
            <form id="logout_form" action="{{ route('logout') }}" method="POST">
                @csrf
                <a class="collapse_item @yield('homeActive')" href="javascript:{}"
                   onclick="document.getElementById('logout_form').submit();">
                    Log out
                </a>
            </form>
            </p>
        @endauth
    </section>
</div>
