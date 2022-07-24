@extends('layouts.app')
@section('title', 'FIITAPIXEL - Pridať foto')
@section('addActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <h2 class="authCardHeader">Photo Upload</h2>
            <div class="card-body">
                <form method="POST" route="{{ url('/photographies/create') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="file">
                            <span>Pridať fotku</span>
                            <input type="file" id="file" name="file" aria-label="File browser example">
                            <span class="file-custom"></span>
                        </label>
                        @error('file')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- <div class="form-group">
                        <label for="myfile">Select a file:</label>
                        <input type="file" id="myfile" name="myfile">
                    </div> -->

                    <br>
                    
                    <div class="form-group">
                        <div class="dropdown">
                            <div class="select">
                                <span>Vyberte súťaž</span>
                                <i class="fa fa-chevron-left"></i>
                            </div>
                            <input id="competition_id" type="text" name="competition_id" class="hidden" value={{ $competitions[0]->id }}>
                            <ul class="dropdown-menu">
                                @foreach($competitions as $competition)
                                    <li id="{{ $competition->id }}">{{ $competition->name }}</li>
                                @endforeach
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

                    <br>

                    <div class="form-group">
                        <div>
                            <p>Pridajte krátky popis</p>
                            <textarea id="description" name="description" class="form-control input"
                                      placeholder="Napíšte nám niečo o fotografii" cols="50" rows="8"
                                      maxlength="255" oninput="countCharacters(255)"></textarea>
                            <div class="hint">
                                <p id="count_characters">0/255</p>
                            </div>
                        </div>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary authButton">Pridať foto</button>
                </form>
            </div>
        </div>
    </div>
@endsection
