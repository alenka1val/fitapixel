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

                <div id="imageListId">
                    @foreach($photos  as $photo)
                        <div id="imageNo{{ $photo->id }}" class="listitemClass">
                            <img class="sortableImg" src="{{ $photo->filename }}" alt="{{ $photo->description }}">
                        </div>
                    @endforeach
                </div>
                <br>
                <p>
                    <i>
                        Ak sa do súťaže zapojilo viac ako 12 fotografií, hodnotí sa iba prvých 12. Ostatné sú označené
                        červeným orámovaním a nehodnotia sa.
                    </i>
                </p>
                <p>
                    <i>
                        Body budú udelené po radoch zľava doprava (12 bodov - fotka v ľavom hornom rohu, 1 bod posledná
                        fotográfia v modrom orámovani).
                    </i>
                </p>
                <br>
                <div style="width: 300px">
                    <form method="POST" action="{{ route('info.voteStore') }}">
                        @csrf
                        <div id="outputDiv">
                            <input hidden id="outputvalues" type="text" value="{{ $votes }}" name="votes"/>
                            <input hidden id="eventId" type="text" value="{{ $eventId }}" name="eventId"/>
                        </div>
                        @error('votes')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        @if(count($photos) > 0)
                            <button type="submit" class="btn btn-primary authButton">
                                Uložiť zmeny
                            </button>
                        @else
                            <strong>V súťaži nie su zaradené žiadne fotografie</strong>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <br>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            $("#imageListId").sortable({
                update: function (event, ui) {
                    getIdsOfImages();
                }//end update
            });
        });

        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function (index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });

            $('#outputvalues').val(values);
        }
    </script>
@endsection
