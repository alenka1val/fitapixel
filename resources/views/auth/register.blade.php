@extends('layouts.app')
@section('title', 'FIITAPIXEL - Registrácia')
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <h2>Registrácia</h2>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="attribute_label">
                            *Celé meno
                        </label>
                        <div>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror input"
                                   name="name" value="{{ old('name') }}" required autocomplete="name"
                                   placeholder="Celé meno" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="attribute_label">
                            *E-mail
                        </label>
                        <div>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror input" name="email"
                                   value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <br>

                    <div class="form-group">
                        <label for="group_id" class="attribute_label">
                            *Vyberať skupinu
                        </label>
                        <div>
                            <select id="group_id" name="group_id" class="form-control input select"
                                    onchange="showAISLogin({{ $groups }})">
                                <option value="" @if( old('group_id') == "") selected @endif disabled hidden>
                                    Vybrať skupinu
                                </option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}"
                                            @if( old('group_id') == $group->id) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>

                    <div class="form-group">
                        <label for="phone" class="attribute_label">
                            Telefón
                        </label>
                        <div>
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
                        <label for="web" class="attribute_label">
                            Webová stránka
                        </label>
                        <div>
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

                    <br>

                    <div class="form-group">
                        <label for="address_street" class="attribute_label">
                            Ulica
                        </label>
                        <div>
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
                        <label for="address_city" class="attribute_label">
                            Mesto
                        </label>
                        <div>
                            <input id="address_city" type="text"
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
                        <label for="address_zip_code" class="attribute_label">
                            PSČ
                        </label>
                        <div>
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

                    <br>

                    <div id="juryDiv" style="display: none">
                        <div class="form-group">
                            <label for="description" class="attribute_label">
                                Napíšte nám niečo o sebe
                            </label>
                            <div>
                                <textarea id="description" name="description" class="form-control input"
                                          placeholder="Napíšte nám niečo o sebe" autofocus cols="40"
                                          rows="10"></textarea>
                            </div>
                        </div>


                        <label class="file">
                            <p class="attribute_label">*Pridať fotku</p>
                            <input type="file" id="file" name="file" aria-label="File browser example">
                            <span class="file-custom"></span>
                        </label>
                    </div>


                    <div class="form-group" id="fiit_user">
                        <br>
                        <label for="ais_uid" class="attribute_label">
                            *AIS login
                        </label>
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

                    <br>

                    <div class="form-group">
                        <label for="password" class="attribute_label">
                            *Heslo
                        </label>
                        <div>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror input" name="password"
                                   required autocomplete="new-password" placeholder="{{ __('Password') }}">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password-confirm" class="attribute_label">
                            *Zopakovať heslo
                        </label>
                        <div>
                            <input id="password-confirm" type="password" class="form-control input"
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Zopakuj heslo">
                        </div>
                    </div>

                    <br>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary authButton">
                                Registrovať
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
