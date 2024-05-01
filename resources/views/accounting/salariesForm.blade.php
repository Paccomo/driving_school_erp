@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif

    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h2 style="margin-right: 0.4cm">Atlyginimų išlaidų suvestinė</h2>
    </div>

    @if ($salariesRegistered)
        <div class="alert alert-danger">
            <p>Šio mėnesio atlyginimai jau įvesti</p>
        </div>
    @else
        <div class="container">
            <p>Darbo vietos kainos skaičiuoklė: <a class="btn btn-secondary btn-sm" target="_blank" href="https://www.sodra.lt/lt/skaiciuokles/darbo_vietos_skaiciuokle?lang=lt">Atidaryti</a></p>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-white bg-secondary">{{ __('Atlyginimų išlaidos') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('accounting.salary.save') }}">
                                @csrf
                                @foreach ($employees as $e)
                                    <div class="row mb-3">
                                        <label for="salary" class="col-md-3 col-form-label align-self-end text-md-end">
                                            {{ $e->person->name }} {{ $e->person->surname }}.<br>
                                            Suma "į rankas": {{ $e->monthly_salary }}€
                                        </label>

                                        <div class="col-md-3">
                                            <label for="salary{{ $e->id }}">Darbo vietos kaina</label>
                                            <input type="number" id="salary{{ $e->id }}"
                                                name="salary{{ $e->id }}" min="0" required
                                                class="form-control @error('salary' . $e->id) is-invalid @enderror"
                                                value="{{ old('salary' . $e->id) }}">

                                            @error('salary' . $e->id)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
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
    @endif
@endsection
