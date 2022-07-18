@extends('layouts.app')
@section('title', 'FIIT a PIXEL - Porodcovia')
@section('juryActive') nav-link-bold @endsection
@section('content')
    <div class="container">
        @include('partials.carusel')
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
