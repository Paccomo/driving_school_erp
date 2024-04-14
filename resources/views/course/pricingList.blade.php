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
        <h1 style="margin-right: 0.4cm">Kainoraštis</h1>
    </div>

    <div class="accordion accordion-flush" id="branchList">
        @foreach ($branches as $branch)
            @if ($branch->compCourses->isNotEmpty() || $branch->catCourses->isNotEmpty())
                <div class="accordion-item">
                    <h2 class="accordion-header" id="branch-{{ $branch->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#branch-{{ $branch->id }}-prices" aria-expanded="false"
                            aria-controls="branch-{{ $branch->id }}-prices">
                            {{ $branch->address }}
                        </button>
                    </h2>
                    <div id="branch-{{ $branch->id }}-prices" class="accordion-collapse collapse"
                        aria-labelledby="branch-{{ $branch->id }}" data-bs-parent="#branchList">
                        <div class="accordion-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($branch->catCourses as $course)
                                    <li class="list-group-item">{{ $course->name }} Kategorija:
                                        <div class="row">
                                            <table class="table table-secondary">
                                                <tbody>
                                                    <tr>
                                                        <td>Pilni kursai -
                                                            {{ $course->theoretical_course_price + $course->practical_course_price }}€
                                                        </td>
                                                        <td>Kursai eksternu - {{ $course->practical_course_price }}€</td>
                                                        <td>
                                                            Papildomos vairavimo pamokos -
                                                            {{ $course->additional_lesson_price }}€</td>
                                                        @if (Auth()->check() && Auth::user()->role == $roleDirector)
                                                            <td>
                                                                <a style="margin-right: 0.4cm"
                                                                    href="{{ route('pricing.edit', ['courseid' => $course->fk_CATEGORICAL_COURSEid, 'branchid' => $branch->id]) }}"
                                                                    class="btn btn-warning">Redaguoti</a>
                                                            </td>
                                                        @endif
                                                    <tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                @endforeach
                                @foreach ($branch->compCourses as $course)
                                    <li class="list-group-item">{{ $course->name }}
                                        <div class="row">
                                            <table class="table table-secondary">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 15%;">Kaina - {{ $course->price }}€</td>
                                                        @if (Auth()->check() && Auth::user()->role == $roleDirector)
                                                            <td>
                                                                <a style="margin-right: 0.4cm"
                                                                    href="{{ route('pricing.edit', ['courseid' => $course->fk_COMPETENCE_COURSEid, 'branchid' => $branch->id]) }}"
                                                                    class="btn btn-warning">Redaguoti</a>
                                                            </td>
                                                        @endif
                                                    <tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
