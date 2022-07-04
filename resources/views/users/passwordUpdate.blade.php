@extends('layouts.app')
@section('profileActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body card-body-left">
                <form method="POST" action="{{ route('users.passwordStore') }}" enctype="multipart/form-data">
                    @csrf
                    @if(!empty($need_ldap))
                        <div class="form-group">
                            <div>
                                <input id="ais_uid" type="text"
                                       class="form-control @error('ais_uid') is-invalid @enderror input" name="ais_uid"
                                       autocomplete="ais_uid"
                                       value="@if(old('ais_uid')){{ old('ais_uid') }}@elseif($user->ais_uid){{ $user->ais_uid }}@endif"
                                       placeholder="AIS prihlasovacie meno">
                                @error('ais_uid')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                    @endif
                    <div class="form-group">
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
                                Uložiť zmeny
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
