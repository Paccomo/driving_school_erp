@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Kurso kainos redagavimas</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pricing.save') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $branchCourse->id }}">
                            <input id="type" type="hidden" class="form-control" name="type"
                                value="categorical">

                            <div class="row mb-3">
                                <label for="theoretical_course_price" class="col-md-3 col-form-label text-md-end">{{ __('Teorijos kaina') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="theoretical_course_price" type="number" step="0.01" min="0.01"
                                        class="form-control @error('theoretical_course_price') is-invalid @enderror" required
                                        value="{{ isset($branchCourse) ? $branchCourse->theoretical_course_price : old('theoretical_course_price') }}"
                                        name="theoretical_course_price">

                                    @error('theoretical_course_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="practical_course_price" class="col-md-3 col-form-label text-md-end">{{ __('Kaina') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="practical_course_price" type="number" step="0.01" min="0.01"
                                        class="form-control @error('practical_course_price') is-invalid @enderror" required
                                        value="{{ isset($branchCourse) ? $branchCourse->practical_course_price : old('practical_course_price') }}"
                                        name="practical_course_price">

                                    @error('practical_course_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="additional_lesson_price" class="col-md-3 col-form-label text-md-end">{{ __('Papildoma vairavimo pamoka') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="additional_lesson_price" type="number" step="0.01" min="0.01"
                                        class="form-control @error('additional_lesson_price') is-invalid @enderror" required
                                        value="{{ isset($branchCourse) ? $branchCourse->additional_lesson_price : old('additional_lesson_price') }}"
                                        name="additional_lesson_price">

                                    @error('additional_lesson_price')
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
