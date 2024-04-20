@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ isset($description) ? 'Redaguoti mokymo kurso aprašymą' : 'Naujas mokymo kurso aprašymas' }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('description.save') }}">
                            @csrf
                            @if (isset($description))
                                @method('PUT')
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $description->id }}">
                            @endif

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ isset($description) ? $description->title : old('title') }}" required>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Turinys') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="5">{{ isset($description) ? $description->description : old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if (isset($description))
                                <input id="course" type="hidden" class="form-control" name="course"
                                    value="{{ $description->fk_COURSEid }}">
                            @else
                                <div class="row mb-3">
                                    <label for="course"
                                        class="col-md-3 col-form-label text-md-end">{{ __('Kursas') }}<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select name="course" class="form-select @error('course') is-invalid @enderror"
                                            id="course" required>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>

                                        @error('course')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label for="distinguished"
                                    class="col-md-3 form-check-label text-md-end">{{ __('Pabrėžtas') }}</label>

                                <div class="col-md-6 ">
                                    <div class="form-check form-switch">
                                        <input id="distinguished" type="checkbox" value="true" class="form-check-input"
                                            role="switch" @error('distinguished') class="is-invalid @enderror"
                                            name="distinguished" @if(isset($description) && $description->is_distinguished == 1) checked @endif)>
                                    </div>

                                    @error('distinguished')
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
