@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('galleryActive') nav-link-bold @endsection
@section('content')
    <div class="container">
        <div class="filter-panel">
            <div class="pad-right-5p">
                <div>
                    <h2>Galéria</h2>
                    <div class="underline"></div>
                </div>
            </div>
        </div>
        <div class="authContainer">
            <div class="filterCard">
                <div class="card-body">
                    <form method="GET">
                        <div class="form-group">
                            <div class="left filter">
                                <span>Ročník</span>
                                <div class="dropdown">
                                    <div class="select">
                                        <span>Vyberte rok</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input id="year" type="text" name="year" class="hidden">
                                    <ul class="dropdown-menu">
                                        @foreach(array_keys($events) as $year)
                                            <li id="{{$year}}">{{$year}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="left filter">
                                <span>Súťaž</span>
                                <div class="dropdown">
                                    <div class="select">
                                        <span>Vyberte súťaž</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input id="year" type="text" name="year" class="hidden">
                                    <ul class="dropdown-menu">
                                        <li id="A">A</li>
                                        <li id="B">B</li>
                                    </ul>
                                    {{--@foreach(array_keys($events) as $year)--}}
                                    {{--   @foreach($events[$year] as $event)--}}
                                    {{--       <ul id="dropdown_{{$year}}" class="dropdown-menu">--}}
                                    {{--           <li id="{{$event->id}}">{{$event->name}}</li>--}}
                                    {{--        </ul>--}}
                                    {{--   @endforeach--}}
                                    {{--@endforeach--}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group button">
                            <button type="submit" class="btn btn-primary authButton">
                                Zobraziť
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($finished)
            <div class="darkPanel">
            <h2>Víťazi</h2>
            <div class="winners">
 
                <img id="photoImg-0" class="galery-image first" onclick="zoomIn('photoImg-0', 0 , '{{ $photos[0]->user_name }}' , '{{ $photos[0]->description }}', '{{ $photos[0]->event_id }}')" src="{{$photos[0]->filename}}" alt="{{$photos[0]->description}}"/>
                <h3 class="first-winner first">{{$photos[0]->user_name}}</h3>
                <!-- <h4 class="first-winner first">{{$photos[0]->description}}</h4> -->

                <img id="photoImg-1" class="galery-image second" onclick="zoomIn('photoImg-1', 1 , '{{ $photos[1]->user_name }}' , '{{ $photos[1]->description }}', '{{ $photos[1]->event_id }}')" src="{{$photos[1]->filename}}" alt="{{$photos[1]->description}}"/>
                <h3 class="second-winner second">{{$photos[1]->user_name}}</h3>
                <!-- <h4 class="second-winner second">{{$photos[1]->description}}</h4> -->

                <img id="photoImg-2" class="galery-image third" onclick="zoomIn('photoImg-2', 2 , '{{ $photos[2]->user_name }}' , '{{ $photos[2]->description }}', '{{ $photos[2]->event_id }}')" src="{{$photos[2]->filename}}" alt="{{$photos[2]->description}}"/>
                <h3 class="third-winner third">{{$photos[2]->user_name}}</h3>
                <!-- <h4 class="third-winner third">{{$photos[2]->description}}</h4> -->
            </div>
        </div>
        @endif
        @if(count($photos)>3)
        <div>
            <div class="galery">
                @for ($i = $finished ? 3 : 0; $i < count($photos); $i++)
                    <img id="photoImg-{{ $i }}" class="galery-image" onclick="zoomIn('photoImg-{{ $i }}', {{ $i }} , '{{ $photos[$i]->user_name }}' , '{{ $photos[$i]->description }}', '{{ $photos[$i]->event_id }}')" src="{{ $photos[$i]->filename }}" alt="{{ $photos[$i]->filename }}"/>
                @endfor
            </div>
        </div>
        @endif
        @if($photos)
        <div id="myModal" class="modal">
            <span class="close"><i class="fa fa-times zoomImageI"></i></span>
            <img class="modal-content" id="img01">
            <div id="caption">
                <i class="fa fa-arrow-left zoomImageArrows" onclick="moveLeft({{ $photos }})"></i>
                <div class="modal-div">
                    <p id="captionText1"></p>
                    <p id="captionText2"></p>
                    <!-- <p id="captionText3"></p> -->
                </div>
                <i class="fa fa-arrow-right zoomImageArrows" onclick="moveRight({{ $photos }})"></i>
            </div>
        </div>
        @endif
    </div>
@endsection
