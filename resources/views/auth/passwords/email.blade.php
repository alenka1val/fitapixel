@extends('layouts.app')
@section('title', 'FIITAPIXEL - Reset hesla')
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body">
                <h2>Reset hesla</h2>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="attribute_label">
                            *E-mail
                        </label>
                        <div>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror input" name="email"
                                   value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="E-mail" autofocus>
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
                                Poslať link na reset hesla
                            </button>
                        </div>
                    </div>
                    @if(!empty($message))
                        <div class="form-group">
                            <strong>
                                {{ $message }}
                            </strong>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
