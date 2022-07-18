@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('competitionActive') nav-link-bold @endsection
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
                                    Súťaž končí: {{ (new DateTime($event->finished_at))->format('d.m.Y')}}
                                </p>
                                <a class="theme-card-link" href="{{ route('photographies.create', ['competition' => $event->url_path]) }}">Zapojiť sa do súťaže</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <br>
    </div>
@endsection
