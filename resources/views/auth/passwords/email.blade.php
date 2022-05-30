@extends('layouts.app')

@section('content')
<div class="authContainer">
    <div class="authCard">
        <div class="card-body">
            <h2>{{ __('Reset Password') }}</h2>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <!-- <label for="email">{{ __('E-Mail Address') }}</label> -->
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
                    <div>
                        <button type="submit" class="btn btn-primary authButton">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
