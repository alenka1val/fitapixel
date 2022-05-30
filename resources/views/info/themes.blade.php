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
                <h2>Témy súťaže</h2>
                <div class="underline"></div>
            </div>
        </div>
        <div class="themes">
            <div>
                <div class="theme-card">
                    <div class="theme-img">
                        <div class="theme-info">
                            <h3>Téma 1</h3>
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
                        <h3>Téma 1</h3>
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
                        <h3>Téma 1</h3>
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
                        <h3>Téma 1</h3>
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
