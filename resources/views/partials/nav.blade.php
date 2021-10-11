<div class="pos-f-t">
    <nav class="navbar navbar-dark bg-dark">
        <div class="row mr-auto">
            <a class="nav-link" href="{{ route('home') }}">
                <!-- <img src="/images/petshop.png" style="max-height: 20px" alt="petShop"> -->
                Home
            </a>
        </div>
        <div class="row ml-auto">
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
    </nav>
</div>