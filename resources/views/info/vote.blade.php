@extends('layouts.app')
@section('title', 'FIIT a PIXEL')
@section('voteActive') nav-link-bold @endsection
@section('content')
    <div class="container">
        <div class="filter-panel">
            <div class="pad-right-5p">
                <div>
                    <h2>{{ $eventName }}</h2>
                    <div class="underline"></div>
                </div>
            </div>
        </div>
        <br>
    </div>
@endsection
