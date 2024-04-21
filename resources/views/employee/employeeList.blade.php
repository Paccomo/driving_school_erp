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
        <h1 style="margin-right: 0.4cm">Darbuotojai</h1>
        <a style="margin-right: 1.5rem;" href="{{ route('register', ['employee']) }}"
            class="btn btn-primary btn-sm h-25 fs-6">Naujas darbuotojas</a>
    </div>

    <div class="list-group">
        @foreach ($employees as $employee)
            <div style="display: inline-flex; align-items: center; margin-right: 10px;">
                <a href="{{ route('employee.index', [$employee->id]) }}"
                    onmouseover="this.style.backgroundColor='rgba(108, 117, 125, 0.6)';"
                    onmouseout="this.style.backgroundColor='';"
                    class="list-group-item list-group-item-action">{{ $employee->fullName }}</a>
                <a style="margin-left: 0.4cm; margin-right: 0.4cm;" href="{{ route('employee.edit', $employee->id) }}"
                    class="btn btn-warning btn-sm">Redaguoti</a>
                <form action="{{ route('employee.destroy', $employee->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Ar tikrai norite pašalinti darbuotoją &quot;{{ $employee->fullName }}&quot;?')">Pašalinti</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
