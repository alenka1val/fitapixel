@extends('layouts.app')

@section('content')
<div class="authContainer">
    <div class="authCard">
        <div class="card-body">
        <h3>{{ __('Confirm Password') }}</h3>
            {{ __('Please confirm your password before continuing.') }}
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-primary authButton">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
