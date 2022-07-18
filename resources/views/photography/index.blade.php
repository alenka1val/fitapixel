@extends('layouts.app')
@section('title', 'FIITAPIXEL - Foto')
@section('content')
<div class="container">
    <h1>FitaPixel</h1>
    <p>It is not a square, it is a sample...</p>
    @if($photographies)
        @foreach($photographies as $photography)
                <p>{{ $photography->filename }}</p>
        @endforeach
    @endif
</div>
@endsection
