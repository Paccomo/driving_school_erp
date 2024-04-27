@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Mokinio redagavimas') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.save') }}">
                            @csrf
                            @method('PUT')
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $client->id }}">
                            <input id="course" type="hidden" class="form-control" name="course"
                                value="{{ $client->fk_COURSEid }}">

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Vardas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name" required
                                        value="{{ $client->person->name }}" autocomplete="given-name">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="surname" class="col-md-4 col-form-label text-md-end">{{ __('Pavardė') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="surname" type="text"
                                        class="form-control @error('surname') is-invalid @enderror" name="surname" required
                                        value="{{ $client->person->surname }}" autocomplete="family-name">

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('El. paštas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $client->account->email }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="pid"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Asmens kodas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="pid" type="text" value="{{ $client->person->pid }}"
                                        class="form-control @error('pid') is-invalid @enderror" name="pid" required>

                                    @error('pid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Adresas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address" required
                                        value="{{ $client->person->address }}" autocomplete="street-address">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="city" class="col-md-4 col-form-label text-md-end">{{ __('Miestas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="city" type="text"
                                        class="form-control @error('city') is-invalid @enderror" name="city" required
                                        value="{{ $client->person->city }}" autocomplete="address-level2">

                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phoneNum" class="col-md-4 col-form-label text-md-end">{{ __('Tel. nr.') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="phoneNum" type="tel"
                                        class="form-control @error('phoneNum') is-invalid @enderror" name="phoneNum"
                                        value="{{ $client->person->phone_number }}" required autocomplete="tel">

                                    @error('phoneNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="branch"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Filialas') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="branch" id="branchSelect"
                                        class="form-select @error('branch') is-invalid @enderror" id="branch" required>
                                        @foreach ($branches as $index => $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ $branch->id == $client->fk_BRANCHid ? 'selected' : '' }}>
                                                {{ $branch->address }}</option>
                                        @endforeach
                                    </select>

                                    @error('branch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
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
