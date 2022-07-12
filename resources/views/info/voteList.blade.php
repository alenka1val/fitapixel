@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('voteActive') nav-link-bold @endsection
@section('content')
    <div class="container">
        <div id="competitions" class="themes info-panel">
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
                                    Vyhodnotené: {{$event->voted}}
                                </p>
                                <a class="theme-card-link" href="{{ route('info.vote', ['competition' => $event->url_path]) }}">Vyhodnotiť súťaž</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <br>
    </div>
@endsection
