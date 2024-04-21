@extends('layouts.app')

@section('content')
    <div style="margin: 0.5cm">
        <div style="margin-bottom: 1cm;">
            <div class="d-flex align-items-left">
                <h4 style="margin-right: 0.4cm">{{ $employee->person->name . ' ' . $employee->person->surname }}</h4>
                <a style="margin-right: 0.4cm" href="{{ route('employee.edit', $employee->id) }}"
                    class="btn btn-warning">Redaguoti</a>
                <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Ar tikrai norite pašalinti darbuotoją &quot;{{ $employee->person->name . ' ' . $employee->person->surname }}&quot;?')">Pašalinti</button>
                </form>
                <a style="margin-left: 0.4cm" href="{{ route('employee.timetable.form', $employee->id) }}"
                    class="btn btn-secondary">Koreguoti tvarkaraštį</a>
                <a style="margin-left: 0.4cm" href="{{ route('pw.form', $employee->id) }}"
                    class="btn btn-outline-secondary">Pakeisti slaptažodį</a>
            </div>
        </div>

        <p style="font-size: 17px"><strong>Pareigos:</strong> @lang('messages.' . $employee->account->role)</p>
        <div class="row">
            <div class="col-7 d-flex justify-content-start">
                <img src="{{ $employee->image }}" style="max-height: 370px; max-width: 100%;" alt="Darbuotojo nuotrauka">
            </div>

            <div class="col">
                <h5 style="font-weight: bold;">Asmeninė informacija</h5>
                <p><span style="font-weight: bold">Vardas: </span>{{ $employee->person->name }}</p>
                <p><span style="font-weight: bold">Pavardė: </span>{{ $employee->person->surname }}</p>
                <p><span style="font-weight: bold">Asmens kodas.: </span>{{ $employee->person->pid }}</p>
                <p><span style="font-weight: bold">Adresas: </span>{{ $employee->person->address }}</p>
                <p><span style="font-weight: bold">Tel. nr.: </span>{{ $employee->person->phone_number }}</p>
                <p><span style="font-weight: bold">El. paštas: </span>{{ $employee->account->email }}</p>
                <p><span style="font-weight: bold">Filialas: </span>{{ $branch->address }}</p>
            </div>
        </div>

        <h5 class="mt-4" style="font-weight: bold;">Darbo laikas</h5>
        <table class="table table-secondary mt-2">
            <tr>
                <th>Savaitės diena</th>
                <th>Darbo pradžia</th>
                <th>Darbo pabaiga</th>
                <th>Pietų pertrauka</th>
            </tr>
            @foreach ($timetable as $weekday => $time)
                <tr>
                    <td>@lang('messages.' . $weekday)</td>
                    <td>{{ isset($time['open']) ? $time['open'] : "--" }}</td>
                    <td>{{ isset($time['close']) ? $time['close'] : "--" }}</td>
                    <td>{{ isset($time['break']) ? $time['break'] : "--" }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
