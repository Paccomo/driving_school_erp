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
                        {{ isset($course) ? 'Redaguoti mokymo kursą' : 'Naujas mokymo kursas' }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('course.save') }}">
                            @csrf
                            @if (isset($course))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $course->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ isset($course) ? $course->name : old('name') }}" required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Esminis aprašymas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="5">{{ isset($course) ? $course->main_description : old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if (!isset($course))
                                <div class="row mb-3">
                                    <label for="categorical"
                                        class="col-md-3 form-check-label text-md-end">{{ __('Kategoriniai kursai') }}</label>

                                    <div class="col-md-6 ">
                                        <div class="form-check form-switch">
                                            <input id="categorical" type="checkbox" value="true" class="form-check-input"
                                                role="switch" @error('categorical') class="is-invalid @enderror"
                                                name="categorical">
                                        </div>

                                        @error('categorical')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

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
