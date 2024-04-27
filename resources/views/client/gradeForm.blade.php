@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Mokyklinio teorijos egzamino įvertis</div>
                    <div class="card-header">
                        {{ $client->fullName }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.grade') }}">
                            @csrf
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $client->id }}">

                            <div class="row mb-3">
                                <label for="grade"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Įvertis') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="grade" type="number" step="1" min="1" max="10"
                                        class="form-control @error('grade') is-invalid @enderror" required
                                        value="{{ is_numeric($client->theory_grade) ? $client->theory_grade : old('grade') }}"
                                        name="grade">

                                    @error('grade')
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
