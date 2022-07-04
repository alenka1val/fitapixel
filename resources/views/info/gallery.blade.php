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
                                    <input type="hidden" name="gender">
                                    <ul class="dropdown-menu">
                                        <li id="2022">2022</li>
                                        <li id="2021">2021</li>
                                        {{--@foreach(array_keys($events) as $year)--}}
                                        {{--    <li id="{{$year}}">{{$year}}</li>--}}
                                        {{--@endforeach--}}
                                    </ul>
                                    <input id="year" type="text" name="year" class="hidden">
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
                                    <input type="hidden" name="gender">
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
                                    <input id="year" type="text" name="year" class="hidden">
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
        {{--@if($finished)--}}
            <div class="darkPanel">
            <h2>Víťazi</h2>
            <div class="winners">
                <img id="photoImg-" class="galery-image first" onclick="zoomIn('photoImg-', 'index', 'fotograf', 'opis', 'súťaž')" src="../images/environment.jpeg"></img>
                <h3 class="first-winner first">1. Janko Mrkvička</h3>

                <img class="galery-image second" src="../images/environment.jpeg"></img>
                <h3 class="second-winner second">2. Jožko Moško</h3>

                <img class="galery-image third" src="../images/environment.jpeg"></img>
                <h3 class="third-winner third">3. Pušiak Lušiak Sokol Omar</h3>
 
                {{--<img class="galery-image first" src="{{$photos[0]->filename}}" alt="{{$photos[0]->description}}"/>--}}
                {{--<h3 class="first-winner first">{{$photos[0]->user_name}}</h3>--}}
                {{--<h4 class="first-winner first">{{$photos[0]->description}}</h4>--}}

                {{--<img class="galery-image second" src="{{$photos[1]->filename}}" alt="{{$photos[1]->description}}"/>--}}
                {{--<h3 class="second-winner second">{{$photos[1]->user_name}}</h3>--}}
                {{--<h4 class="second-winner second">{{$photos[1]->description}}</h4>--}}

                {{--<img class="galery-image third" src="{{$photos[2]->filename}}" alt="{{$photos[2]->description}}"/>--}}
                {{--<h3 class="third-winner third">{{$photos[2]->user_name}}</h3>--}}
                {{--<h4 class="third-winner third">{{$photos[2]->description}}</h4>--}}
            </div>
        </div>
        {{--@endif--}}

        <div>
            <div class="galery">
                <img id="photoImg-" class="galery-image" onclick="zoomIn('photoImg-', 'index', 'fotograf', 'opis', 'súťaž')" src="../images/environment.jpeg"></img>
                <img class="galery-image" src="../images/environment.jpeg"></img>
                <img class="galery-image" src="../images/environment.jpeg"></img>
                <img class="galery-image" src="../images/environment.jpeg"></img>
                <img class="galery-image" src="../images/environment.jpeg"></img>
                <img class="galery-image" src="../images/environment.jpeg"></img>
                {{--@for ($i = $finished ? 3 : 0; $i < count($photos); $i++)--}}
                {{--    <img class="galery-image" src="{{$photos[$i]->filename}}" alt="{{$photos[$i]->filename}}"/>--}}
                {{--@endfor--}}
            </div>
        </div>
        <div id="myModal" class="modal">
            <span class="close"><i class="fa fa-times zoomImageI"></i></span>
            <img class="modal-content" id="img01">
            <div id="caption">
                <i class="fa fa-arrow-left zoomImageArrows" onclick="moveLeft({{ $photoList }})"></i>
                <div class="modal-div">
                    <p id="captionText1"></p>
                    <p id="captionText2"></p>
                    <p id="captionText3"></p>
                </div>
                <i class="fa fa-arrow-right zoomImageArrows" onclick="moveRight({{ $photoList }})"></i>
            </div>
        </div>
    </div>
@endsection
