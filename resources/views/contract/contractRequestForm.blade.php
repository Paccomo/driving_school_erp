@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Sutarties įkėlimas
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('contract.save') }}" enctype="multipart/form-data">
                            @csrf
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $contract->id }}">
                            <input id="client" type="hidden" class="form-control" name="client"
                                value="{{ $client->id }}">

                            <div class="row mb-3">
                                <label for="file" class="col-md-3 col-form-label text-md-end">{{ __('Sutartis') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="file" type="file" accept="application/pdf"
                                        class="form-control @error('file') is-invalid @enderror" name="file" required>

                                    @error('file')
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
