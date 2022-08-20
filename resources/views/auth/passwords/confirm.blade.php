@extends('layouts.app')
@section('title', 'FIITAPIXEL - Overenie hesla')
@section('content')
<div class="authContainer">
    <div class="authCard">
        <div class="card-body">
        <h2>Potvrednie hesla</h2>
            Pred potvrdením prosím skontrolujte svoje heslo
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="form-group">
                    <label for="password" class="attribute_label">
                        *Heslo
                    </label>
                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror input" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
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
                            Potvrdiť
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    @if (Route::has('password.request'))
                        <!-- <a class="btn btn-link" href="{{ route('password.request') }}">
                            Zabudli ste heslo?
                        </a> -->
                        <button class="btn btn-secondary authButton" onClick="window.location='{{ route("password.request") }}'">
                            Zabudli ste heslo?
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
