@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('competitionActive') nav-link-bold @endsection
@section('content')
    <div class="container">
        <div id="home">
            <div id="header-main">
                <h1>Súťaž</h1>
                <br>
                <div class="mySlides fade">
                    <div class="numbertext">1 / 3</div>
                    <p>Súťaž trvá:
                        <wbr>
                        od 30.3.2021
                        <wbr>
                        do 12.10.2021
                    </p>
                </div>
                <div class="mySlides fade">
                    <div class="numbertext">2 / 3</div>
                    <p>Súťaž trvá: do 12.10.2021</p>
                </div>
                <div class="mySlides fade">
                    <div class="numbertext">3 / 3</div>
                    <p>Súťaž trvá: do 12.10.2021</p>
                </div>
                <div style="text-align:center">
                    <span class="dot" onclick="resetSlides(1)"></span>
                    <span class="dot" onclick="resetSlides(2)"></span>
                    <span class="dot" onclick="resetSlides(3)"></span>
                </div>
                <!-- <p>Súťaž trvá:<wbr> od 30.3.2021<wbr> do 12.10.2021</p> -->
            </div>
            <div class="main-button">
                    <button class="main-button yellow-background last" onclick="redirect('#competitions')">
                        Zapojiť sa do súťaže
                    </button>
            </div>
        </div>
        @foreach($contents as $content)
            @if($loop->index % 2 == 0)
                <div class="info-panel-left col-2">
                    <div>
                        <img class="info-image" src="{{$content->photo}}"
                             alt="{{$content->photo}}"/>
                    </div>
                    <div class="pad-left-5p">
                        <div>
                            <h2>{{$content->name}}</h2>
                            <div class="underline"></div>
                        </div>
                        <p>
                            {{$content->text}}
                        </p>
                    </div>
                </div>
            @else
                <div class="info-panel col-2">
                    <div class="pad-right-5p">
                        <div>
                            <h2>{{$content->name}}</h2>
                            <div class="underline"></div>
                        </div>
                        <p>
                            {{$content->text}}
                        </p>
                    </div>
                    <div>
                        <img class="info-image" src="{{$content->photo}}"
                             alt="{{$content->photo}}"/>
                    </div>
                </div>
            @endif
        @endforeach

        <br>

        <div id="competitions" class="themes @if(count($contents) % 2 == 1) info-panel @else info-panel-left @endif">
            @foreach($events as $event)
                <div>
                    <div class="theme-card">
                        <div class="theme-img">
                            <div class="theme-info">
                                <h3>{{$event->name}}</h3>
                                <p>
                                    {{$event->description}}
                                </p>
                                <p>
                                    Súťaž končí: {{$event->finished_at}}
                                </p>
                                <a class="theme-card-link" href="{{ route('photographies', ['competition' => $event->url_path]) }}">Zapojiť sa do súťaže</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <br>
    </div>
@endsection
