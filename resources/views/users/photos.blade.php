@extends('layouts.app')
@section('profileActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body card-body-left">
                @foreach($photos as $event)
                    <div class="userGallery">
                        <p>
                            <b>
                                {{ $event['name'] }}
                            </b>
                        </p>
                    @foreach($event['photos'] as $photo)
                        <div class="userGalleryDetail">
                            <img src="{{ $photo->filename }}" alt="{{ $photo->filename }}">
                            <div class="userGalleryDetailRight">
                                <p>
                                    {{ $photo->description }}
                                </p>
                                <p>
                                    <a href="{{ route('info.gallery', ['event_id' => $photo->event_id]) }}">
                                        Pozrieť celú súťaž
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
