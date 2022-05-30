@extends('layouts.app')
@section('title', 'FIITAPIXEL - Pravidlá')
@section('rulesActive') active @endsection
@section('content')
    <div class="content margin_div">
        <div class="article">
            <h2>LDAP connection</h2>

            <p style="color: darkred">
                Status: {{ $status }}

                @dd($entries)
            </p>
        </div>
    </div>
@endsection
