@extends('layouts.app')

@section('content')
    <div style="margin: 0.5cm">
        <div style="margin-bottom: 1cm;">
            <div class="d-flex align-items-left">
                <h4 style="margin-right: 0.4cm">{{ $branch->address }}</h4>
                @if (Auth()->check() && Auth::user()->role == $roleDirector)
                    <a style="margin-right: 0.4cm" href="{{ route('branch.edit', $branch->id) }}"
                        class="btn btn-warning">Redaguoti</a>
                    <form action="{{ route('branch.destroy', $branch) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Ar tikrai norite pašalinti šį filialą? Šiuo veiksmu bus pašalinti ir filialio darbuotojai!')">Pašalinti</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col{{ $branch->image !== null ? '-7' : '' }}">
                @if ($branch->phone_number !== null || $branch->email !== null)
                    <h5 style="font-weight: bold;">Kontaktai</h5>
                    @if ($branch->phone_number !== null)
                        <p><span style="font-weight: bold">Tel. nr.: </span>{{ $branch->phone_number }}</p>
                    @endif

                    @if ($branch->email !== null)
                        <p><span style="font-weight: bold">El. paštas: </span>{{ $branch->email }}</p>
                    @endif
                @endif

                @if (count($branch->timetable) > 0)
                    <h5 style="margin-top: 1.4cm; font-weight: bold;">Darbo laikas</h5>
                    <table class="table table-borderless">
                        @foreach ($weekdays as $idx => $weekday)
                            @if (isset($branch->timetable[$idx]))
                                <tr style="line-height: 10px; min-height: 10px; height: 10px;">
                                    <td>@lang('messages.' . $weekday):</td>
                                    <td>
                                        @if (isset($branch->timetable[$idx]['open']))
                                            {{ substr($branch->timetable[$idx]['open'], 0, 5) }} -
                                        @else
                                            00:00 -
                                        @endif

                                        @if (isset($branch->timetable[$idx]['close']))
                                            {{ substr($branch->timetable[$idx]['close'], 0, 5) }}.
                                        @else
                                            23:59
                                        @endif

                                        @if (isset($branch->timetable[$idx]['break']))
                                            Pietų pertrauka (1 val.) nuo
                                            {{ substr($branch->timetable[$idx]['break'], 0, 5) }}.
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                @endif
            </div>

            @if ($branch->image !== null)
                <div class="col-5">
                    <img src="{{ $branch->image }}" style="max-height: 370px;" alt="Filialo nuotrauka">
                </div>
            @endif
        </div>

        @if (isset($branch->description))
            <div class="card border-light" style="margin-top: 1.4cm;">
                <h5 class="card-header" style="font-weight: bold;">Aprašymas</h5>
                <div class="card-body">
                  <p class="card-text">{{ $branch->description }}</p>
                </div>
            </div>
        @endif

        @if ($branch->competenceCourses->isNotEmpty() || $branch->categoricalCourses->isNotEmpty())
            <h5 style="margin-top: 1.4cm; font-weight: bold;">Organizuojami kursai</h5>
            <ul class="list-group list-group-flush">
                @foreach ($branch->categoricalCourses as $course)
                    <li class="list-group-item">
                        {{ $course->name }} Kategorija
                        @guest
                            <button class="btn btn-sm btn-secondary" style="position: absolute; right: 0">Registruotis</button>
                        @endguest
                    </li>
                @endforeach

                @foreach ($branch->competenceCourses as $course)
                    <li class="list-group-item">
                        {{ $course->name }}
                        @guest
                            <button class="btn btn-sm btn-secondary" style="position: absolute; right: 0">Registruotis</button>
                        @endguest
                    </li>
                @endforeach
            </ul>
        @else
            <h5 style="margin-top: 1.4cm; font-weight: bold;">Šiuo metu filialas neorganizuoja jokių kursų</h5>
        @endif
    </div>
@endsection
