@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('content')
    <div class="container">
        <div id="home">
            <div id="header-main">
                <h1>Rozhodujú o Vás</h1>
                <br\>
                <!-- <p>Súťaž trvá:<wbr> od 30.3.2021<wbr> do 12.10.2021</p> -->
            </div>
            <div class="main-button">
                <button class="main-button yellow-background last">
                    Zapojiť sa do súťaže
                </button>
            </div>
        </div>
        @foreach($jury_list as $jury)
            @if($loop->index % 2 == 0)
                <div class="info-panel-left col-2">
                    <div>
                        <img class="info-image" src="{{ $jury->photo }}" alt="{{ $jury->name }}">
                    </div>
                    <div class="pad-left-5p">
                        <div>
                            <h2>{{ $jury->name }}</h2>
                            <div class="underline"></div>
                        </div>
                        <p>
                            {{ $jury->description }}
                        </p>
                    </div>
                </div>
            @else
                <div class="info-panel col-2">
                    <div class="pad-right-5p">
                        <div>
                            <h2>{{ $jury->name }}</h2>
                            <div class="underline"></div>
                        </div>
                        <p>
                            {{ $jury->description }}
                        </p>
                    </div>
                    <div>
                        <img class="info-image" src="{{ $jury->photo }}" alt="{{ $jury->name }}">
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
