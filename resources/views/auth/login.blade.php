@extends('layouts.app')
@section('title', 'FIITAPIXEL - Prihlásenie')
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <h2>Prihlásenie</h2>
            <div class="card-body">
                @if(!empty($message))
                    <div class="form-group">
                        <strong>
                            {{ $message }}
                        </strong>
                    </div>
                    <br>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="attribute_label">
                            *E-Mail Address or AIS login
                        </label>
                        <div>
                            <input id="email" type="text"
                                   class="form-control @error('email') is-invalid @enderror input" name="email"
                                   value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="E-Mail Address or AIS login" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="attribute_label">
                            *Heslo
                        </label>
                        <div>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror input" name="password"
                                   required autocomplete="current-password" placeholder="Heslo">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember"
                                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Zapamätať heslo?
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary authButton">
                                Prihlásiť
                            </button>
                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <button class="btn btn-secondary authButton"
                            onClick="window.location='{{ route("register") }}'">
                        Registrácia
                    </button>
                </div>
                <div class="form-group">
                    @if (Route::has('password.request'))
                        <button class="btn btn-secondary authButton"
                                onClick="window.location='{{ route("password.request") }}'">
                            Zabudli ste heslo?
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
