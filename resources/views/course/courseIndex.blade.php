@extends('layouts.app')

@section('content')
    <div style="margin: 0.5cm">
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <div style="margin-bottom: 1cm;">
                <div class="d-flex align-items-left">
                    <a style="margin-right: 0.4cm;" href="{{ route('course.edit', $course->id) }}"
                        class="btn btn-sm btn-warning">Redaguoti</a>
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Ar tikrai norite pašalinti mokymo kursą &quot;{{ $course->course->name }}&quot;?')">Pašalinti</button>
                    </form>
                </div>
        @endif

        <div class="jumbotron">
            <h1 class="display-4">
                {{ $course->course->name }}
                @isset($course->additionToName)
                    {{ $course->additionToName }}
                @endisset
            </h1>
            <p class="lead">{{ $course->course->main_description }}</p>
            <hr class="my-4">
            @guest
                <p class="lead">
                    <a class="btn btn-primary btn-lg" href="{{route('course.register')}}" role="button">Registruotis</a>
                </p>
            @endguest
        </div>
    </div>
@endsection