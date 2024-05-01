@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif

    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Instruktoriaus paskaitų laikai</h1>
    </div>

    @if ($times->isNotEmpty())
        <div class="list-group">
            @foreach ($times as $time)
                <div style="display: inline-flex; align-items: center; margin-right: 10px;">
                    <li class="list-group-item list-group-item-action">@lang('messages.' . $time->day). {{ $time->from }} -
                        {{ $time->to }}</li>
                    <form action="{{ route('employee.time.destroy', $time->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Ar tikrai norite pašalinti instruktoriaus paskaitos laiką?')">Pašalinti</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-danger">
            <h5>Instruktorius neturi paskaitoms išskirtų laikų</h5>
        </div>
    @endif

    <h4 style="margin-top: 2cm;">Pridėti laiką:</h4>
    <form action="{{ route('employee.time.destroy', $time->id) }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="employee", value="{{ $employee }}">

        <select name="weekday" class="form-select form-select-sm mb-2">
            @foreach ($weekdays as $weekday)
                <option value="{{ $weekday }}">@lang('messages.' . $weekday)</option>
            @endforeach
        </select>

        <div class="row mb-2">
            <div class="col">
                <label for="from" class="form-label">Nuo</label>
                <input type="time" id="from" name="from" class="form-control form-control-sm">
            </div>
            <div class="col">
                <label for="to" class="form-label">Iki</label>
                <input type="time" id="to" name="to" class="form-control form-control-sm">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-sm">Pridėti</button>
    </form>
@endsection
