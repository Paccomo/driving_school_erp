@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Darbuotojo redagavimas') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('employee.save') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input id="id" type="hidden" class="form-control" name="id"
                                    value="{{ $employee->id }}">

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Vardas') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name" required
                                        value="{{ $employee->person->name }}" autocomplete="given-name">

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
                                        value="{{ $employee->person->surname }}" autocomplete="family-name">

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
                                        value="{{ $employee->account->email }}" required autocomplete="email">

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
                                    <input id="pid" type="text" value="{{ $employee->person->pid }}"
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
                                        value="{{ $employee->person->address }}" autocomplete="street-address">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="city"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Miestas') }}<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="city" type="text"
                                        class="form-control @error('city') is-invalid @enderror" name="city" required
                                        value="{{ $employee->person->city }}" autocomplete="address-level2">

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
                                        value="{{ $employee->person->phone_number }}" required autocomplete="tel">

                                    @error('phoneNum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="branch" class="col-md-4 col-form-label text-md-end">{{ __('Filialas') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="branch" id="branchSelect"
                                        class="form-select @error('branch') is-invalid @enderror" id="branch" required>
                                        @foreach ($branches as $index => $branch)
                                            <option value="{{ $branch->id }}" {{ $branch->id == $employee->fk_BRANCHid ? 'selected' : '' }}>{{ $branch->address }}</option>
                                        @endforeach
                                    </select>

                                    @error('branch')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">


                                <label for="image"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Nuotrauka') }}</label>

                                <div class="col-md-6">
                                    @if (isset($employee) && $employee->image)
                                        <img src="{{ $employee->image }}" alt="Filialo nuotrauka"
                                            style="max-width: 200px;">
                                    @endif

                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="employmentTime"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Etatas') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="employmentTime" id="employmentTimeSelect"
                                        class="form-select @error('employmentTime') is-invalid @enderror"
                                        id="employmentTime" required>
                                        <option value="1" {{ $employee->employment_time == 1 ? 'selected' : '' }}>1</option>
                                        <option value="0.75" {{ $employee->employment_time == 0.75 ? 'selected' : '' }}>0.75</option>
                                        <option value="0.5" {{ $employee->employment_time == 0.5 ? 'selected' : '' }}>0.5</option>
                                    </select>

                                    @error('employmentTime')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="salary"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Alga.') }}<span
                                        class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="salary" type="number" step="0.01" min="0"
                                        class="form-control @error('salary') is-invalid @enderror" required
                                        value="{{ $employee->monthly_salary }}" name="salary">

                                    @error('salary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Pareigos') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="role" class="form-select @error('role') is-invalid @enderror"
                                        id="role" required>
                                        @foreach ($roles as $value => $displayName)
                                            <option value="{{ $value }}" {{ $value == $employee->account->role ? 'selected' : '' }}>@lang('messages.' . $displayName)</option>
                                        @endforeach
                                    </select>

                                    @error('role')
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
