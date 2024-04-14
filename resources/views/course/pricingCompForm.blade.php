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
                                value="competence">

                            <div class="row mb-3">
                                <label for="price" class="col-md-3 col-form-label text-md-end">{{ __('Kaina') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="price" type="number" step="0.01" min="0.01"
                                        class="form-control @error('price') is-invalid @enderror" required
                                        value="{{ isset($branchCourse) ? $branchCourse->price : old('price') }}"
                                        name="price">

                                    @error('price')
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
