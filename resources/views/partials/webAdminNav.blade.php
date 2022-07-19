<div class="pos-f-t">
    <nav class="navbar fiitapixel_nav" style="background-color: rgb(255, 186, 90)">
        <div class="nav_grid left_grid large_grid">
            <h1>
                <img id="nav_img" src="{{asset( '/images/web_admin.png' )}}" title="domov"
                     alt="FIITaPIXEL" onclick="home()">
            </h1>
        </div>
        <div class="nav_icon"></div>
        <div class="nav_grid right_grid large_grid">
            <div>
            </div>
            <a class="nav-link @yield('adminEventActive')" href="{{ route('admin.eventIndex') }}">
                Súťaže
            </a>
            <a class="nav-link @yield('adminUserActive')" href="{{ route('admin.userIndex') }}">
                Používatelia
            </a>
            <a class="nav-link @yield('adminPhotoActive')" href="{{ route('admin.photoIndex') }}">
                Fotky
            </a>
            <a class="nav-link @yield('adminGroupActive')" href="{{ route('admin.groupIndex') }}">
                Skupiny
            </a>
            <a class="nav-link @yield('adminContentActive')" href="{{ route('admin.contentIndex') }}">
                Content
            </a>
            <a class="nav-link @yield('adminSponsorActive')" href="{{ route('admin.sponsorIndex') }}">
                Sponzori
            </a>
        </div>
        <div>
            <a class="nav_icon" onclick="collapseNav()">
                <i id="burgerMenu" class="fa fa-bars"></i>
                <i id="burgerX" class="fa fa-times"></i>
            </a>
        </div>
    </nav>
    <section id="collapse">
        <div id="collapse-items" style="background-color: rgb(255, 186, 90)">
            <p class="p-nav"><a class="collapse_item @yield('adminEventActive')" href="{{ route('admin.eventIndex') }}">Súťaže</a></p>
            <p class="p-nav"><a class="collapse_item @yield('adminUserActive')" href="{{ route('admin.userIndex') }}">Používatelia</a></p>
            <p class="p-nav"><a class="collapse_item @yield('adminPhotoActive')" href="{{ route('admin.photoIndex') }}">Fotky</a></p>
            <p class="p-nav"><a class="collapse_item @yield('adminGroupActive')" href="{{ route('admin.groupIndex') }}">Skupiny</a></p>
            <p class="p-nav"><a class="collapse_item @yield('adminContentActive')" href="{{ route('admin.contentIndex') }}">Content</a></p>
            <p class="p-nav"><a class="collapse_item @yield('adminSponsorActive')" href="{{ route('admin.sponsorIndex') }}">Sponzori</a></p>
        </div>

    </section>
</div>
