@extends('layouts.app')
@section('profileActive') nav-link-bold @endsection
@section('content')
    <div class="authContainer">
        <div class="authCard">
            <div class="card-body card-body-left">
                <div class="form-group profile-buttons">
                    <form id="logout_form" action="{{ route('users.photos') }}" method="GET">
                        @csrf
                        <button class="btn btn-primary authButton btn-profile" type="submit">
                            Moje fotografie
                        </button>
                    </form>
                    <form id="logout_form" action="{{ route('users.create') }}" method="GET">
                        @csrf
                        <button class="btn btn-primary authButton btn-profile" type="submit">
                            Upraviť profil
                        </button>
                    </form>
                    <form id="logout_form" action="{{ route('users.passwordCreate') }}" method="GET">
                        @csrf
                        <button class="btn btn-primary authButton btn-profile" type="submit">
                            Zmeniť heslo
                        </button>
                    </form>
                    <form id="logout_form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary authButton btn-profile" type="submit">
                            Odhlásiť sa
                        </button>
                    </form>
                </div>
                <br>
                @if($user->group == "jury")
                    <div class="form-group">
                        <img class="galery-image" src="{{ $user->photo }}" alt="{{ $user->name }}">
                    </div>
                @endif
                <div class="form-group">
                    <p>
                        Meno: {{ $user->name }}
                    </p>
                    <p>
                        Email: {{ $user->email }}
                    </p>
                    <p>
                        Telefón: {{ $user->phone }}
                    </p>
                </div>
                <br>
                <div class="form-group">
                    <p>
                        Web: {{ $user->web }}
                    </p>
                </div>
                <br>
                <div class="form-group">
                    <p>
                        Ulica: {{ $user->address_street }}
                    </p>
                    <p>
                        Mesto: {{ $user->address_city }}
                    </p>
                    <p>
                        PSČ: {{ $user->address_zip_code }}
                    </p>
                </div>
                <br>
                <div class="form-group">
                    @if(!empty($user['need_ldap']))
                        <p>
                            AIS ID: {{ $user->ais_uid }}
                        </p>
                    @endif
                    <p>
                        Skupina: {{ $user->group }}
                    </p>
                </div>
                @if($user->group == "jury")
                    <br>
                    <div class="form-group">
                        <p>
                            Opis: {{ $user->description }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
