@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Registracija į mokymus</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('contract.joinCourse') }}">
                            @csrf
                            <input id="course" type="hidden" class="form-control" name="course"
                                value="{{ $course->id }}">

                            <div class="row mb-3">
                                <div class="col-md-6 mx-auto">
                                    <h5 class="text-secondary">{{ $course->name }}</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">{{ __('Vardas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="surname" class="col-md-3 col-form-label text-md-end">{{ __('Pavardė') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="surname" type="text"
                                        class="form-control @error('surname') is-invalid @enderror" name="surname"
                                        value="{{ old('surname') }}" required>

                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-3 col-form-label text-md-end">{{ __('El. paštas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phoneNum" class="col-md-3 col-form-label text-md-end">{{ __('Tel. nr.') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="phoneNum" type="tel"
                                        class="form-control @error('phoneNum') is-invalid @enderror" name="phoneNum"
                                        required autocomplete="tel">

                                    @error('phoneNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if ($branch instanceof \App\Models\Branch)
                                <input id="branch" type="hidden" class="form-control" name="branch"
                                    value="{{ $branch->id }}">
                            @else
                                @if ($branch->isNotEmpty())
                                    <div class="row mb-3">
                                        <label for="branch"
                                            class="col-md-3 col-form-label text-md-end">{{ __('Filialas') }}<span
                                                class="text-danger">*</span></label>

                                        <div class="col-md-6">
                                            <select name="branch" id="branchSelect"
                                                class="form-select @error('branch') is-invalid @enderror" id="branch"
                                                required>
                                                @foreach ($branch as $index => $br)
                                                    <option value="{{ $br->branch->id }}">{{ $br->branch->address }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('branch')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <div class="row mb-3">
                                <label for="comment"
                                    class="col-md-3 col-form-label text-md-end">{{ __('Papildoma infromacija') }}</label>

                                <div class="col-md-6">
                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>

                                    @error('comment')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            @if ($isCategorical)
                                <div class="row mb-3">
                                    <label for="improvement"
                                        class="col-md-3 form-check-label text-md-end">{{ __('Tobulinimosi sutartis¹') }}</label>

                                    <div class="col-md-6 ">
                                        <div class="form-check form-switch">
                                            <input id="improvement" type="checkbox" value="true" class="form-check-input"
                                                role="switch" @error('improvement') class="is-invalid @enderror"
                                                name="improvement">
                                        </div>

                                        @error('improvement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="noTheory"
                                        class="col-md-3 form-check-label text-md-end">{{ __('Be teorijos (eksternu)') }}</label>

                                    <div class="col-md-6 ">
                                        <div class="form-check form-switch">
                                            <input id="noTheory" type="checkbox" value="true"
                                                class="form-check-input" role="switch"
                                                @error('noTheory') class="is-invalid @enderror" name="noTheory">
                                        </div>

                                        @error('noTheory')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Išsaugoti') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        Po registracijos patvirtinimo yra būtina nuvykti į filialą pasirašyti mokymo sutartį.
                    </div>
                    @if ($isCategorical)
                        <div class="card-footer text-muted">
                            ¹ Tobulinimosi sutartis yra pasirašoma, kuomet kursai jau yra pabaigti vairavimo mokykloje
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
