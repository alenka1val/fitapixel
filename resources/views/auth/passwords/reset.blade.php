@extends('layouts.app')
@section('title', 'FIITAPIXEL - Reset hesla')
@section('content')
<div class="authContainer">
    <div class="authCard">
        <div class="card-body">
            <h2>Reset hesla</h2>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email" class="attribute_label">
                        *E-mail
                    </label>
                    <div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>

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
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">

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
                               placeholder="Zopakovať heslo">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-primary authButton">
                            Resetovať heslo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
