@extends('layouts.app')
@section('content')
<div class="authContainer">
    <div class="authCard">
    <h3 class="authCardHeader">Photo Upload</h3>
    <div class="card-body">
    <form method="POST" route="{{ url('/photographies') }}" enctype="multipart/form-data">
        @csrf
        <label class="file">
            <input type="file" id="file" name="file" aria-label="File browser example">
            <span class="file-custom"></span>
        </label>
        <!-- <div class="form-group">
            <label for="myfile">Select a file:</label>
            <input type="file" id="myfile" name="myfile">
        </div> -->

        <div class="form-group">
            <label for="theme">Theme</label>
                <input id="theme"
                    type="text"
                    class="form-control @error('theme') is-invalid @enderror"
                    name="theme"
                    value="{{ old('theme') }}"
                    placeholder=""
                    autocomplete="theme"
                    required
                >
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="description" class="col-form-label">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary authButton">Submit</button>
    </form>

    </div>
    </div>
    
</div>
@endsection