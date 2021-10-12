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
    <div id="myModal" class="modal">
        <span class="close"><i class="fa fa-times zoomImageI"></i></span>
        <img class="modal-content" id="img01">
        <div id="caption">
            <i class="fa fa-arrow-left zoomImageArrows" onclick="moveLeft({{ $photoList }})"></i>
            <div class="modal-div">
                <p id="captionText1"></p>
                <p id="captionText2"></p>
                <p id="captionText3"></p>
            </div>
            <i class="fa fa-arrow-right zoomImageArrows" onclick="moveRight({{ $photoList }})"></i>
        </div>
    </div>
@endsection
