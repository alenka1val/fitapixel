@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('content')
    <div class="container">
        @include('partials.carusel')
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
        <div id="price">
            <br>
        </div>
    </div>
@endsection
