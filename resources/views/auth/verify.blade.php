@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Overiť hsoju emailovú adresu</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Na vašu emilovú adresu bol odoslaný link na reset hesla
                        </div>
                    @endif

                    Pred potvredím prosím skontrolujte svoju emailovú adresu
                    Ak vám nebol doručený email
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">cliknite sem pre opätovné odoslanie</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
