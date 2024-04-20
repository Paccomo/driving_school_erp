@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Slaptažodžio keitimas') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('pw.save') }}">
                            @csrf
                            @if ($account != null)
                                <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $account->id }}">
                                @isset($account->name)
                                    <h5 class="card-title mb-4">{{ $account->name }}</h5>
                                @endisset
                            @endif
                            <div class="row mb-3">
                                <label for="old"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Senas slaptažodis') }}</label>

                                <div class="col-md-6">
                                    <input id="old" type="password"
                                        class="form-control @error('old') is-invalid @enderror" name="old" required
                                        autocomplete="current-password">

                                    @error('old')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="new"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Naujas slaptažodis') }}</label>

                                <div class="col-md-6">
                                    <input id="new" type="password"
                                        class="form-control @error('new') is-invalid @enderror" name="new" required>

                                    @error('new')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="new_confirmation"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pakartoti naują slaptažodį') }}</label>

                                <div class="col-md-6">
                                    <input id="new_confirmation" type="password"
                                        class="form-control @error('new_confirmation') is-invalid @enderror"
                                        name="new_confirmation" required>

                                    @error('new_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
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
