@extends('layouts.app')
@section('title', 'FIITAPIXEL - Fotoalbum')
@section('galleryActive') active @endsection
{{--@dd($photoList)--}}
@section('content')
    <div class="content margin_div">
        <div class="photoContainer">
            @foreach($photoList as $photo)
                <div>
                    <div class="photoImg"
                         onclick="zoomIn('photoImg-{{ $loop->index }}', {{ $loop->index }}, '{{ $photo->photograph }}',
                             '{{ $photo->description }}', '{{ $photo->theme }}')"
                         title="kliknite pre zoom: {{ $photo->description }}">
                        <img  id="photoImg-{{ $loop->index }}" src="{{ $photo->filename }}" alt="{{ $photo->description }}">
                        <i class="photoI fa fa-search-plus"></i>
                        <p class="photoP">{{ $photo->photograph }} : {{ $photo->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
