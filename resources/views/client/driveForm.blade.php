@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Naujas mokymo lapas</div>
                    <div class="card-header">
                        {{ $client->fullName }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.drive') }}" target="_blank">
                            @csrf
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $client->id }}">

                            <div class="row mb-3">
                                <label for="carNum"
                                    class="col-md-5 col-form-label text-md-end">{{ __('Automobilio valstybiniai nr.') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="carNum" type="text"
                                        class="form-control @error('carNum') is-invalid @enderror" name="carNum"
                                        value="{{ old('carNum') }}" required>

                                    @error('carNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="model"
                                    class="col-md-5 col-form-label text-md-end">{{ __('Automobilio markė modelis') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="model" type="text"
                                        class="form-control @error('model') is-invalid @enderror" name="model"
                                        value="{{ old('model') }}" required>

                                    @error('model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="gearbox"
                                    class="col-md-5 col-form-label text-md-end">{{ __('Automobilio pavarų dėžės tipas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="gearbox" type="text"
                                        class="form-control @error('gearbox') is-invalid @enderror" name="gearbox"
                                        value="{{ old('gearbox') }}" required>

                                    @error('gearbox')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-6">
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
