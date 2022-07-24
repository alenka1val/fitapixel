@extends('layouts.app')
@section('title', 'FIITAPIXEL - Reset hesla')
@section('profileActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body card-body-left">
                <form method="POST" action="{{ route('users.passwordStore') }}" enctype="multipart/form-data">
                    @csrf
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
