@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        {{ $title }}
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('contract.client.save') }}">
                            @csrf
                            <input id="type" type="hidden" class="form-control" name="type"
                                value="{{ $type }}">

                            <div class="row mb-3">
                                <label for="comment"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Komentaras') }}</label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>

                                    @error('comment')
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
