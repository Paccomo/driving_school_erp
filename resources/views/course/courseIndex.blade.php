@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif
    <div style="margin: 0.5cm">
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <div style="margin-bottom: 1cm;">
                <div class="d-flex align-items-left">
                    <a style="margin-right: 0.4cm;" href="{{ route('description.list', $course->id) }}"
                        class="btn btn-secondary btn-sm mr-2">Aprašymai</a>
                    <a style="margin-right: 0.4cm;" href="{{ route('course.edit', $course->id) }}"
                        class="btn btn-sm btn-warning">Redaguoti</a>
                    <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Ar tikrai norite pašalinti mokymo kursą &quot;{{ $course->course->name }}&quot;?')">Pašalinti</button>
                    </form>
                </div>
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
                    <a class="btn btn-primary btn-lg" href="{{ route('course.register', ['course' => $course->id]) }}" role="button">Registruotis</a>
                </p>
            @endguest
        </div>

        <div class="mt-5 mb-5">
            @forelse($highlights as $highlight)
                <div class="container">
                    <div class="row">
                        <div class="col-auto">
                            <i class="fa-solid fa-circle-info fa-2x"></i>
                        </div>
                        <div class="col">
                            <h4>{{ $highlight->title }}</h4>
                            <p>{{ $highlight->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>

        <div class="mt-5 mb-5">
            @forelse($info as $desc)
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h4>{{ $desc->title }}</h4>
                            <p>{{ $desc->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>

    </div>
@endsection
