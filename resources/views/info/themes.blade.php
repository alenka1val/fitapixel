@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('content')
<div class="container">
    <div class="info-panel info-panel-start">
        <!-- <div>
            <img class="info-image" src="../images/environment.jpeg"></img>         
        </div> -->
        <div class="pad-right-5p">
            <div>
                <h2>Galéria</h2>
                <div class="underline"></div>
            </div>
        </div>
        <div class="filterContainer">
            <div class="filterCard">
                <div class="card-body">
                    <form method="GET">
                        <div class="form-group">
                            <!-- <div>
                                <select class="customSelect" id="cars">
                                    <option value="volvo">Volvo</option>
                                    <option value="saab">Saab</option>
                                    <option value="opel">Opel</option>
                                    <option value="audi">Audi</option>
                                </select>
                            </div> -->

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
                            </div>
                            <span class="msg"></span>
                            <input id="year" type="text" name="year" class="hidden">
                        </div>
                        <!-- <div clsass="form-group">
                            <button class="btn btn-secondary authButton" onClick="window.location='{{ route("register") }}'">
                                Registrácia
                            </button>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
        <div class="themes">
            <div>
                <div class="theme-card">
                    <div class="theme-img">
                        <div class="theme-info">
                            <h3>Súťaž 1</h3>
                            <p>
                                Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.
                            </p>
                            <a class="theme-card-link" href="{{ route('info.themes') }}">Fotografie</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="theme-card">
                <div class="theme-img">
                    <div class="theme-info">
                        <h3>Súťaž 2</h3>
                        <p>
                            Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.
                        </p>
                        <a class="theme-card-link" href="{{ route('info.themes') }}">Fotografie</a>
                    </div>
                </div>
            </div>
            <div class="theme-card">
                <div class="theme-img">
                    <div class="theme-info">
                        <h3>Súťaž 3</h3>
                        <p>
                            Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.
                        </p>
                        <a class="theme-card-link" href="{{ route('info.themes') }}">Fotografie</a>
                    </div>
                </div>
            </div>
            <div class="theme-card">
                <div class="theme-img">
                    <div class="theme-info">
                        <h3>Súťaž 4</h3>
                        <p>
                            Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.
                        </p>
                        <a class="theme-card-link" href="{{ route('info.themes') }}">Fotografie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
