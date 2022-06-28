@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('galleryActive') nav-link-bold @endsection
@section('content')
<div class="container">
    <div class="filter-panel">
        <div class="pad-right-5p">
            <div>
                <h2>Galéria</h2>
                <div class="underline"></div>
            </div>
        </div>
    </div>
    <div class="authContainer">
        <div class="filterCard">
            <div class="card-body">
                <form method="GET">
                    <div class="form-group">
                        <div class="left filter">
                            <span>Ročník</span>
                            <div class="dropdown">
                                <div class="select">
                                    <span>Vyberte rok</span>
                                    <i class="fa fa-chevron-left"></i>
                                </div>
                                <input type="hidden" name="gender">
                                <ul class="dropdown-menu">
                                    <li id="2021">2021</li>
                                    <li id="2022">2022</li>
                                </ul>
                                <input id="year" type="text" name="year" class="hidden">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="left filter">
                            <span>Súťaž</span>
                            <div class="dropdown">
                                <div class="select">
                                    <span>Vyberte súťaž</span>
                                    <i class="fa fa-chevron-left"></i>
                                </div>
                                <input type="hidden" name="gender">
                                <ul class="dropdown-menu">
                                    <li id="2021">A</li>
                                    <li id="2022">B</li>
                                </ul>
                                <input id="year" type="text" name="year" class="hidden">
                            </div>
                        </div>
                    </div>
                    <div class="form-group button">
                        <button type="submit" class="btn btn-primary authButton">
                            Zobraziť
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="darkPanel">
        <h2>Víťazi</h2>
        <div class="winners">
            <img class="galery-image first" src="../images/environment.jpeg"></img>
            <h3 class="first-winner first">1. Janko Mrkvička</h3>

            <img class="galery-image second" src="../images/environment.jpeg"></img>
            <h3 class="second-winner second">2. Jožko Moško</h3>

            <img class="galery-image third" src="../images/environment.jpeg"></img>
            <h3 class="third-winner third">3. Pušiak Lušiak Sokol Omar</h3>
        </div>
    </div>

    <div>
        <div class="galery">
            <img class="galery-image" src="../images/environment.jpeg"></img>
            <img class="galery-image" src="../images/environment.jpeg"></img>
            <img class="galery-image" src="../images/environment.jpeg"></img>
            <img class="galery-image" src="../images/environment.jpeg"></img>
            <img class="galery-image" src="../images/environment.jpeg"></img>
            <img class="galery-image" src="../images/environment.jpeg"></img>
        </div>
    </div>
</div>
@endsection