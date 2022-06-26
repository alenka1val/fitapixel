@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('content')
    <div class="container">
        <div id="home">
            <div id="header-main">
                <h1>FIITAPIXEL</h1>
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
                <button class="main-button yellow-background last">
                    Zapojiť sa do súťaže
                </button>
            </div>
        </div>
        <div id="about" class="info-panel-left col-2">
            <div>
                <img class="info-image" src="{{$contents['history']['photo']}}" alt="{{$contents['history']['photo']}}"/>
            </div>
            <div class="pad-left-5p">
                <div>
                    <h2>Čo je FIITAPIXEL?</h2>
                    <div class="underline"></div>
                </div>
                <p>
                    {{$contents['about']['text']}}
                </p>
            </div>
        </div>
        <div id="history" class="info-panel col-2">
            <div class="pad-right-5p">
                <div>
                    <h2>Ako vznikol FIITAPIXEL?</h2>
                    <div class="underline"></div>
                </div>
                <p>
                    {{$contents['history']['text']}}
                </p>
            </div>
            <div>
                <img class="info-image" src="{{$contents['history']['photo']}}" alt="{{$contents['history']['photo']}}"/>
            </div>
        </div>
        <div id="price">
            <br>
        </div>

    </div>
@endsection
