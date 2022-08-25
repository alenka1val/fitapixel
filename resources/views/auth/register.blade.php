@extends('layouts.app')
@section('title', 'FIITAPIXEL - Registrácia')
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <h2>{{ __('Register') }}</h2>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="has-input">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror input"
                                   name="name" value="{{ old('name') }}"  autocomplete="name"
                                   placeholder="Celé meno" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="has-input">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror input" name="email"
                                   value="{{ old('email') }}"  autocomplete="email" placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="left filter">
                                <div class="dropdown drop-card">
                                    <div class="select">
                                        <span>Vyberte skupinu</span>
                                        <i class="fa fa-chevron-left"></i>
                                    </div>
                                    <input id="group_id" type="text" name="group_id" class="hidden" @error('group_id') is-invalid @enderror>
                                    <ul class="dropdown-menu">
                                        @foreach($groups as $group)
                                            <li id="{{ $group->id }}">@if( old('group_id') == $group->id) selected @endif{{ $group->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <!-- <div class="form-group">
                        <div>
                            <select id="group_id" name="group_id" class="form-control input select"
                                    onchange="showAISLogin({{ $groups }})">
                                <option value="" @if( old('group_id') == "") selected @endif disabled hidden>
                                    Vyberte skupinu
                                </option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                            @if( old('group_id') == $group->id) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <div class="has-input">
                            <input id="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror input" name="phone"
                                   value="{{ old('phone') }}" autocomplete="phone" placeholder="Telefón" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="has-input">
                            <input id="web" type="text" class="form-control @error('web') is-invalid @enderror input"
                                   name="web" value="{{ old('web') }}" autocomplete="web" placeholder="Webová stránka"
                                   autofocus>
                            @error('web')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="has-input">
                            <input id="address_street" type="text"
                                   class="form-control @error('address_street') is-invalid @enderror input"
                                   name="address_street" value="{{ old('address_street') }}"
                                   autocomplete="address_street" placeholder="Ulica" autofocus>
                            @error('address_street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="has-input">
                            <input id="address_city" type="address_city"
                                   class="form-control @error('address_city') is-invalid @enderror input"
                                   name="address_city" value="{{ old('address_city') }}" autocomplete="address_city"
                                   placeholder="Mesto" autofocus>
                            @error('address_city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="has-input">
                            <input id="address_zip_code" type="text"
                                   class="form-control @error('address_zip_code') is-invalid @enderror input"
                                   name="address_zip_code" value="{{ old('address_zip_code') }}"
                                   autocomplete="address_zip_code" placeholder="PSČ" autofocus>
                            @error('address_zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div id="juryDiv" style="display: none">
                        <div class="form-group">
                            <div>
                            <textarea id="description" name="description" class="form-control input"
                                      placeholder="Napíšte nám niečo o sebe" autofocus cols="40" rows="10"></textarea>
                            </div>
                        </div>
                        <label class="file">
                            <p>Pridať fotku</p>
                            <input type="file" id="file" name="file" aria-label="File browser example">
                            <span class="file-custom"></span>
                        </label>
                    </div>
                    <div class="form-group" id="fiit_user">
                        <div class="has-input">
                            <input id="ais_uid" type="text"
                                   class="form-control @error('ais_uid') is-invalid @enderror input" name="ais_uid"
                                   autocomplete="ais_uid" value="{{ old('ais_uid') }}"
                                   placeholder="AIS prihlasovacie meno">
                            @error('ais_uid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                    <!-- <label for="password">{{ __('Password') }}</label> -->
                        <div class="has-input">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror input" name="password"
                                    autocomplete="new-password" placeholder="{{ __('Password') }}">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                    <!-- <label for="password-confirm">{{ __('Confirm Password') }}</label> -->
                        <div>
                            <input id="password-confirm" type="password" class="form-control input"
                                   name="password_confirmation"  autocomplete="new-password"
                                   placeholder="{{ __('Confirm Password') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary authButton">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById("group_id").onchange();
    </script>
@endsection
