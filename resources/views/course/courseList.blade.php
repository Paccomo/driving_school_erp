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
        <h1 style="margin-right: 0.4cm">Organizuojami kursai</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('course.add') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti
                kursą</a>
        @endif
    </div>

    <ul class="list-group list-group-flush">
        @foreach ($categoricalCourses as $course)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('course.index', $course->id) }}">{{ $course->course->name }} Kategorija</a>
                @if (Auth()->check() && Auth::user()->role == $roleDirector)
                    <div>
                        <a href="{{ route('description.list', $course->id) }}" class="btn btn-secondary btn-sm mr-2">Aprašymai</a>
                        <a href="{{ route('course.edit', $course->id) }}" class="btn btn-warning btn-sm mr-2">Redaguoti</a>
                        <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Ar tikrai norite pašalinti mokymo kursą &quot;{{ $course->course->name }}&quot;?')">Pašalinti</button>
                        </form>
                    </div>
                @endif
                @guest
                    <button class="btn btn-sm btn-secondary">Registruotis</button>
                @endguest
            </li>
        @endforeach

        @foreach ($competenceCourses as $course)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('course.index', $course->id) }}">{{ $course->course->name }}</a>
                @if (Auth()->check() && Auth::user()->role == $roleDirector)
                    <div>
                        <a href="{{ route('description.list', $course->id) }}" class="btn btn-secondary btn-sm mr-2">Aprašymai</a>
                        <a href="{{ route('course.edit', $course->id) }}" class="btn btn-warning btn-sm mr-2">Redaguoti</a>
                        <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Ar tikrai norite pašalinti mokymo kursą &quot;{{ $course->course->name }}&quot;?')">Pašalinti</button>
                        </form>
                    </div>
                @endif
                @guest
                    <button class="btn btn-sm btn-secondary">Registruotis</button>
                @endguest
            </li>
        @endforeach
    </ul>
@endsection
