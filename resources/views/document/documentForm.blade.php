@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ $formTitle }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.save') }}" enctype="multipart/form-data">
                            @csrf
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $clientId }}">

                            <input id="docType" type="hidden" class="form-control" name="docType"
                                value="{{ $docType }}">

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Galioja iki') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="date" type="date"
                                        class="form-control @error('date') is-invalid @enderror" name="date"
                                        value="{{ old('date') }}" required>


                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="doc"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Dokumentas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="doc" type="file" accept="image/*, application/pdf"
                                        class="form-control @error('doc') is-invalid @enderror" name="doc">

                                    @error('doc')
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
