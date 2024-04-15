@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ isset($video) ? 'Redaguoti vaizdo įrašą' : 'Naujas vaizdo įrašas' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('video.save') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($video))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $video->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ isset($video) ? $video->link->title : old('title') }}" required>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="video_file"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Vaizdo įrašas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="video_file" type="file" accept="video/*"
                                        class="form-control @error('video_file') is-invalid @enderror" name="video_file"
                                        @empty($videoFile) required @endempty>

                                    @error('video_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="mt-2">
                                        @isset($videoFile)
                                            <input type="hidden" value="1" name="previousVideo">
                                            <video width="420" height="236" controls>
                                                <source src="{{ $videoFile }}" type="video/mp4">
                                            </video>
                                        @endisset
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Išsaugoti') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
