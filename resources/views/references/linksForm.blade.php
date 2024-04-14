@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ isset($link) ? 'Redaguoti informacinę nuorodą' : 'Nauja informacinė nuoroda' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('link.save') }}">
                            @csrf
                            @if (isset($link))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $link->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ isset($link) ? $link->link->title : old('title') }}" required>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="source"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Nuoroda') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="source" type="text"
                                        class="form-control @error('source') is-invalid @enderror" name="source"
                                        value="{{ isset($link) ? $link->link->link : old('source') }}" required>

                                    @error('source')
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
                </div>
            </div>
        </div>
    </div>
@endsection
