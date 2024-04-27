@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Priimti mokėjimą</div>
                    <div class="card-header">
                        {{ $client->fullName }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.payment') }}">
                            @csrf
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $client->id }}">

                            <div class="row mb-3">
                                <label for="pay"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Suma') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="pay" type="number" step="0.01" min="0.01"
                                        class="form-control @error('grade') is-invalid @enderror" required
                                        value="{{ old('pay') }}" name="pay">

                                    @error('pay')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="sumW"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Suma žodžiais') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="sumW" type="text"
                                        class="form-control @error('sumW') is-invalid @enderror" name="sumW"
                                        value="{{ old('sumW') }}" required>

                                    @error('sumW')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="reason"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Paskirtis') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="reason" type="text"
                                        class="form-control @error('reason') is-invalid @enderror" name="reason"
                                        value="{{ old('reason') }}" required>

                                    @error('reason')
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
