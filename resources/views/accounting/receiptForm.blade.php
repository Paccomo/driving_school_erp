@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ $title }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route() }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="amount" class="col-md-3 col-form-label text-md-end">{{ __('Suma') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input type="number" id="amount" name="amount" min="0" required
                                        class="form-control @error('amount') is-invalid @enderror"
                                        value="{{ old('amount') }}">

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="receiptDate" class="col-md-3 col-form-label text-md-end">{{ __('Data') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input type="date" id="receiptDate" name="receiptDate" required
                                        class="form-control @error('receiptDate') is-invalid @enderror"
                                        value="{{ old('receiptDate') }}">

                                    @error('receiptDate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="reason"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Priežastis') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason"
                                        rows="5">{{ old('reason') }}</textarea>

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
