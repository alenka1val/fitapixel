@extends('layouts.app')

@section('content')
<div class="authContainer">
    <div class="authCard">
        <h2>{{ __('Register') }}</h2>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <!-- <label for="name">{{ __('Name') }}</label> -->
                    <div>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror input" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Name') }}" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="email">{{ __('E-Mail Address') }}</label> -->
                    <div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="password">{{ __('Password') }}</label> -->
                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
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
                        <input id="password-confirm" type="password" class="form-control input" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
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
