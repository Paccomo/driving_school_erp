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
                        {{ isset($slide) ? 'Redaguoti teorijos skaidres' : 'Naujos teorijos skaidrės' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('slides.save') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($slide))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $slide->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ isset($slide) ? $slide->link->title : old('title') }}" required>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="slideFile" class="col-md-3 col-form-label text-md-end">{{ __('Skaidrės') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="slideFile" type="file" accept=".pptx, .pdf, .ppt, .odp"
                                        class="form-control @error('slideFile') is-invalid @enderror" name="slideFile"
                                        @empty($slideFile) required @endempty>

                                    @error('slideFile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="mt-2">
                                        @isset($slideFile)
                                            <input type="hidden" value="1" name="previousSlide">
                                            <p>{{ $slideFile }}</p>
                                        @endisset
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="order"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Eiliškumo numeris') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="order" type="number" step="1" min="1"
                                        class="form-control @error('order') is-invalid @enderror" required
                                        value="{{ isset($slide) ? $slide->order : old('order') }}"
                                        name="order">

                                    @error('order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                    <div class="card-footer">
                        Leistini skaidrių formatai: .pptx, .pdf, .ppt, .odp
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
