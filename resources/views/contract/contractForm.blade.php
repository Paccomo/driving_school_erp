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
                        <form method="POST" action="{{ route('contract.saveRequestless') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="client" class="col-md-3 col-form-label text-md-end">{{ __('Mokinys') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="client" id="clientSelect"
                                        class="form-select @error('client') is-invalid @enderror" id="client" required>
                                        @foreach ($clients as $index => $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->person->name }} {{ $client->person->surname }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('client')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="title"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Pavadinimas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror" name="title"
                                        value="{{ old('title') }}" required>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

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
