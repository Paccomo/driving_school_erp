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
        <h1 style="margin-right: 0.4cm">Kurso "{{ $courseName }}" aprašymai</h1>
        <a style="margin-right: 1.5rem;" href="{{ route('description.add') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti
            aprašymą</a>
    </div>

    <div style="margin: 0.5cm">
        <h4>Pabrėžti aprašymai:</h4>
        @if ($distinguished->isNotEmpty())
            <ul class="list-group list-group-flush">
                @foreach ($distinguished as $desc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('description.index', $desc->id) }}">{{ $desc->title }}</a>
                        <div>
                            <a href="{{ route('description.edit', $desc->id) }}"
                                class="btn btn-warning btn-sm mr-2">Redaguoti</a>
                            <form action="{{ route('description.destroy', $desc->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Ar tikrai norite pašalinti kurso aprašyma &quot;{{ $desc->title }}&quot;?')">Pašalinti</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Pabrėžtų aprašymų kursas neturi</p>
        @endif
        <br>

        <h4>Paprasti aprašymai:</h4>
        @if ($regular->isNotEmpty())
            <ul class="list-group list-group-flush">
                @foreach ($regular as $desc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('description.index', $desc->id) }}">{{ $desc->title }}</a>
                        <div>
                            <a href="{{ route('description.edit', $desc->id) }}"
                                class="btn btn-warning btn-sm mr-2">Redaguoti</a>
                            <form action="{{ route('description.destroy', $desc->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Ar tikrai norite pašalinti kurso aprašyma &quot;{{ $desc->title }}&quot;?')">Pašalinti</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Paprastų aprašymų kursas neturi</p>
        @endif
    </div>
@endsection
