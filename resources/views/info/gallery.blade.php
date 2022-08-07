@extends('layouts.app')
@section('title', 'FIIT a PIXEL - Galéria')
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
                    @if(count($photos)==0)
                        <h4 class="photo-not-found left">Nenašli sa žiadne fotky !</h4>
                    @endif
                    <form method="GET">
                        <div class="form-group">
                            <div class="left filter">
                                <span>Ročník</span>
                                <div class="dropdown selected_year">
                                    <div class="select">
                                        <span>{{ $selected_year }}</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input id="selected_year" type="text" name="selected_year" class="hidden"
                                           value="{{ $selected_year }}">
                                    <ul id="year-list" class="dropdown-menu">
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
                                        <span id="selected-item-name">{{ $selected_event['name'] }}</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input id="selected_event" type="text" name="selected_event" class="hidden"
                                           value="{{ $selected_event['id'] }}">
                                    <ul id="event_list" class="dropdown-menu">
                                        @foreach($events[$selected_year] as $event)
                                            <li id="{{$event['id']}}">{{ $event['name'] }}</li>
                                        @endforeach
                                    </ul>
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
        @if($finished && count($photos)>=3)
            <div class="darkPanel">
                <h2>Víťazi</h2>
                <div class="winners">
                    <img id="photoImg-0" class="galery-image first"
                         onclick="zoomIn('photoImg-0', 0 , '{{ $photos[0]->user_name }}' , '{{ $photos[0]->description }}', '{{ $photos[0]->event_id }}', '{{ $photos[0]->place }}')"
                         src="{{$photos[0]->filename}}" alt="{{$photos[0]->description}}"/>
                    <h3 class="first-winner first">{{$photos[0]->user_name}}</h3>
                    <h4 class="first-winner first"><i>{{$photos[0]->place}}. miesto</i></h4>

                    <img id="photoImg-1" class="galery-image second"
                         onclick="zoomIn('photoImg-1', 1 , '{{ $photos[1]->user_name }}' , '{{ $photos[1]->description }}', '{{ $photos[1]->event_id }}', '{{ $photos[1]->place }}')"
                         src="{{$photos[1]->filename}}" alt="{{$photos[1]->description}}"/>
                    <h3 class="second-winner second">{{$photos[1]->user_name}}</h3>
                    <h4 class="second-winner second"><i>{{$photos[1]->place}}. miesto</i></h4>

                    <img id="photoImg-2" class="galery-image third"
                         onclick="zoomIn('photoImg-2', 2 , '{{ $photos[2]->user_name }}' , '{{ $photos[2]->description }}', '{{ $photos[2]->event_id }}', '{{ $photos[2]->place }}')"
                         src="{{$photos[2]->filename}}" alt="{{$photos[2]->description}}"/>
                    <h3 class="third-winner third">{{$photos[2]->user_name}}</h3>
                    <h4 class="third-winner third"><i>{{$photos[2]->place}}. miesto</i></h4>
                </div>
            </div>
        @endif
        @if(count($photos)>3)
            <div>
                <div class="galery">
                    @for ($i = $finished ? 3 : 0; $i < count($photos); $i++)
                        <img id="photoImg-{{ $i }}" class="galery-image"
                             onclick="zoomIn('photoImg-{{ $i }}', {{ $i }} , '{{ $photos[$i]->user_name }}' , '{{ $photos[$i]->description }}', '{{ $photos[$i]->event_id }}', '{{ $photos[$i]->place }}')"
                             src="{{ $photos[$i]->filename }}" alt="{{ $photos[$i]->filename }}"/>
                        <h4><i>{{$photos[2]->place}}. miesto</i></h4>
                    @endfor
                </div>
            </div>
        @endif
        @if($photos)
            <div id="myModal" class="modal">
                <span class="close"><i class="fa fa-times zoomImageI"></i></span>
                <img class="modal-content" id="img01">
                <div id="caption">
                    <i class="fa fa-arrow-left zoomImageArrows left_arrow_move" onclick="moveLeft({{ $photos }})"></i>
                    <div class="modal-div">
                        <p id="captionText1"></p>
                        <p id="captionText2"></p>
                        <p id="captionText3"></p>
                    </div>
                    <i class="fa fa-arrow-right zoomImageArrows right_arrow_move" onclick="moveRight({{ $photos }})"></i>
                </div>
            </div>
        @endif
    </div>
    <script>
        const events = <?php echo(json_encode($events)); ?>;
    </script>
@endsection
