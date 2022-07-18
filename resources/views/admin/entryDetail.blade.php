@extends('layouts.app')
@section('title', 'WebAdmin - ' . $title)
@section($active) nav-link-bold @endsection
@section('content')
    <div class="authContainer" style="min-width: 300px">
        <div class="authCard">
            <h2>{{ $header }}</h2>
            <div class="card-body">
                <form method="POST" action="{{ route($storeURL, ['id' => $entry['id']]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @foreach($cols as $col)
                        @if($col['type'] == "textarea")
                            <div class="form-group">
                                <div>
                                    <label for="{{ $col['name'] }}"
                                           style="font-weight: bold">@if($col['required'] == "required" )
                                            *@endif{{ $col['text'] }}</label>
                                    <textarea id="{{ $col['name'] }}" name="{{ $col['name'] }}"
                                              class="form-control input" {{ $col['required'] }}
                                              placeholder="{{ $col['placeholder'] }}" cols="40" rows="10"
                                              title="@if(isset($col['pattern'])){{ $col['example'] }}
                                              @else {{ $col['text'] }} @endif">{{ old( $col['name'], isset($entry[$col['name']]) ? $entry[$col['name']] : "" )  }}</textarea>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="{{ $col['name'] }}"
                                       style="font-weight: bold">
                                    @if($col['required'] == "required" )*@endif{{ $col['text'] }}</label>
                                <div>
                                    <input id="{{ $col['name'] }}" type="{{ $col['type'] }}"
                                           class="form-control @error($col['name']) is-invalid @enderror input"
                                           name="{{ $col['name'] }}"
                                           value="{{ old( $col['name'], isset($entry[$col['name']]) ? $entry[$col['name']] : "" ) }}"
                                           {{ $col['required'] }}
                                           placeholder="{{ $col['text'] }}"
                                           @if(isset($col['pattern']))pattern="{{ $col['pattern'] }}" @endif
                                           title="@if(isset($col['pattern'])){{ $col['example'] }}@else {{ $col['text'] }} @endif">
                                </div>
                            </div>
                        @endif
                        @error($col['name'])
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endforeach

                    <br>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary authButton">
                                @if($entry['id'] == 'new') Pridať @else Potvrdiť zmeny @endif
                            </button>
                        </div>
                    </div>
                </form>
                @if($entry['id'] != 'new')
                    <form name="deleteForm" action="{{ route( $deleteURL, ['id' => $entry['id']]) }}"
                          method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-secondary authButton"
                                onclick="return confirm('{{ $confirm }}:\n\'{{ $entry[$confirmAttr] }}\'?')">
                            Odstrániť
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
