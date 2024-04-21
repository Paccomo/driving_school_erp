@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Darbuotojo tvarkaraščio redagavimas') }}</div>
                    <div class="card-header bg-light">
                        {{ $employee->person->name . ' ' . $employee->person->surname }}. Savaitės valandų suma:
                        <span style="font-weight: 700;">{{ $employee->work_hours }}</span> val.</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('employee.timetable.save') }}">
                            @csrf
                            @method('PUT')
                            <input id="id" type="hidden" class="form-control" name="id"
                                value="{{ $employee->id }}">

                            @foreach ($weekdays as $weekday)
                                <div class="row mb-3">
                                    <label for="{{ $weekday }}"
                                        class="col-md-3 col-form-label align-self-end text-md-end">@lang('messages.' . $weekday)</label>

                                    @foreach ($types as $type)
                                        <div class="col-md-3">
                                            <label for="{{ $weekday . '_' . $type }}">@lang('messages.' . $type)</label>
                                            <input type="time" id="{{ $weekday . '_' . $type }}"
                                                name="{{ $weekday . '_' . $type }}"
                                                value="{{ isset($timetable[$weekday][$type]) ? $timetable[$weekday][$type] : old($weekday . '_' . $type) }}"
                                                class="form-control @error($weekday . '_' . $type) is-invalid @enderror">

                                            @error($weekday . '_' . $type)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

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
