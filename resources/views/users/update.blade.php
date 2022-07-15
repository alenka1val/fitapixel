@extends('layouts.app')
@section('profileActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body card-body-left">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror input"
                                   name="name"
                                   value="@if(old('name')){{ old('name') }}@elseif($user->name){{ $user->name }}@endif"
                                   required autocomplete="name"
                                   placeholder="Celé meno" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror input" name="email"
                                   value="@if(old('email')){{ old('email') }}@elseif($user->email){{ $user->email }}@endif"
                                   required autocomplete="email" placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div>
                            <select id="group_id" name="group_id" class="form-control input select"
                                    onchange="showAISLogin()">
                                <option value="" @if( old('group_id') == "") selected @endif disabled hidden>
                                    Vyberte skupinu
                                </option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                            @if( old('group_id') == $group->id || $user->group_id == $group->id) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div>
                            <input id="phone" type="text"
                                   class="form-control @error('phone') is-invalid @enderror input" name="phone"
                                   value="@if(old('phone')){{ old('phone') }}@elseif($user->phone){{ $user->phone }}@endif"
                                   autocomplete="phone" placeholder="Telefón" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <input id="web" type="text" class="form-control @error('web') is-invalid @enderror input"
                                   name="web"
                                   value="@if(old('web')){{ old('web') }}@elseif($user->web){{ $user->web }}@endif"
                                   autocomplete="web" placeholder="Webová stránka"
                                   autofocus>
                            @error('web')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div>
                            <input id="address_street" type="text"
                                   class="form-control @error('address_street') is-invalid @enderror input"
                                   name="address_street"
                                   value="@if(old('address_street')){{ old('address_street') }}@elseif($user->address_street){{ $user->address_street }}@endif"
                                   autocomplete="address_street" placeholder="Ulica" autofocus>
                            @error('address_street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <input id="address_city" type="address_city"
                                   class="form-control @error('address_city') is-invalid @enderror input"
                                   name="address_city"
                                   value="@if(old('address_city')){{ old('address_city') }}@elseif($user->address_city){{ $user->address_city }}@endif"
                                   autocomplete="address_city"
                                   placeholder="Mesto" autofocus>
                            @error('address_city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <input id="address_zip_code" type="text"
                                   class="form-control @error('address_zip_code') is-invalid @enderror input"
                                   name="address_zip_code"
                                   value="@if(old('address_zip_code')){{ old('address_zip_code') }}@elseif($user->address_zip_code){{ $user->address_zip_code }}@endif"
                                   autocomplete="address_zip_code" placeholder="PSČ" autofocus>
                            @error('address_zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div id="fiit_user">
                        <div class="form-group">
                            <br>
                            <div>
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
                            <div>
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror input"
                                       name="password"
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
                                       name="password_confirmation" autocomplete="new-password"
                                       placeholder="{{ __('Confirm Password') }}">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div id="juryDiv" style="display: none">
                        <div class="form-group">
                            <div>
                            <textarea id="description" name="description" class="form-control input"
                                      placeholder="Napíšte nám niečo o sebe" autofocus cols="40"
                                      rows="10">{{ $user->description }}</textarea>
                            </div>
                        </div>
                        <label class="file">
                            <p>Pridať fotku</p>
                            <input type="file" id="file" name="file" aria-label="File browser example">
                            <span class="file-custom"></span>
                        </label>
                    </div>
                    <br>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary authButton">
                                Uložiť zmeny
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
        showAISLogin()
    </script>
@endsection
