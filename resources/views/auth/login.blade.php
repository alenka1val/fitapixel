@extends('layouts.app')

@section('content')
<div class="authContainer">
    <div class="authCard">
        <h2>{{ __('Login') }}</h2>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <!-- <label for="email" >{{ __('E-Mail Address') }}</label> -->
                    <div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input" name="email" value="{{ old('email') }}" required autocomplete="email"  placeholder="{{ __('E-Mail Address') }}" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="password" >{{ __('Password') }}</label> -->
                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
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
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-primary authButton">
                            {{ __('Login') }}
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-secondary authButton" onClick="window.location='{{ route("register") }}'">
                        Registrácia
                    </button>
                </div>
                <div class="form-group">
                    @if (Route::has('password.request'))
                        <button class="btn btn-secondary authButton" onClick="window.location='{{ route("password.request") }}'">
                            {{ __('Forgot Your Password?') }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
