@extends('layouts.app')
@section('addActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <h3 class="authCardHeader">Photo Upload</h3>
            <div class="card-body">
                <form method="POST" route="{{ url('/photographies/create') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="file">
                            <p>Pridať fotku</p>
                            <input type="file" id="file" name="file" aria-label="File browser example">
                            <span class="file-custom"></span>
                        </label>
                    </div>
                    <!-- <div class="form-group">
                        <label for="myfile">Select a file:</label>
                        <input type="file" id="myfile" name="myfile">
                    </div> -->

                    <br>

                    <div class="form-group">
                        <div>
                            <p>Vyberte súťaž</p>
                            <select id="competition_id" name="competition_id" class="form-control input select"
                                    onchange="showAISLogin()">
                                <option value="" @if( old('competition_id') == "") selected @endif disabled hidden>
                                    Vyberte súťaž
                                </option>
                                @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}"
                                            @if(
                                            (!is_null($competition_id) and $competition_id == $competition->id)
                                            or old('competition_id') == $competition->id
                                            ) selected @endif>{{ $competition->name }}</option>
                                @endforeach
                            </select>
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
                    </div>
                    <button type="submit" class="btn btn-primary authButton">Pridať foto</button>
                </form>

            </div>
        </div>

    </div>
@endsection
