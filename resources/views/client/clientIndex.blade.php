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
    <div style="margin: 0.5cm">
        <div style="margin-bottom: 1cm;">
            <div class="d-flex align-items-left">
                <h4 style="margin-right: 0.4cm">{{ $client->person->name . ' ' . $client->person->surname }}</h4>
            </div>
        </div>

        <h5 style="font-weight: bold;">Instruktorius: {{ $instructor }}</h5>
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('client.instructor') }}">
                    <select name="inst" id="instSelect" class="form-select" required>
                        @foreach ($allInstructors as $inst)
                            <option value="{{ $inst->id }}">
                                {{ $inst->person->name . ' ' . $inst->person->surname }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <h5 style="margin-top: 0.5cm; font-weight: bold;">Asmeninė informacija</h5>
        <div class="row">
            <div class="col">
                <span style="font-weight: bold">Vardas:</span>
                <p>{{ $client->person->name }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Pavardė:</span>
                <p>{{ $client->person->surname }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Asmens kodas:</span>
                <p>{{ $client->person->pid }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Adresas:</span>
                <p>{{ $client->person->address }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Tel. nr.:</span>
                <p>{{ $client->person->phone_number }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">El. paštas:</span>
                <p>{{ $client->account->email }}</p>
            </div>
        </div>

        <h5 style="font-weight: bold;">Mokinio kursų informacija</h5>
        <div class="row">
            <div class="col">
                <span style="font-weight: bold">Filialas:</span>
                <p>{{ $client->branch->address }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Kursas:</span>
                <p>{{ $client->course->name }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Pabaigęs kursus:</span>
                <p>
                    @if ($client->currently_studying == 1)
                        <span style="color: red">&#10060;</span>
                    @else
                        <span style="color: green">&#10004;</span>
                    @endif
                </p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Leidžiama praktika</span>
                <p>
                    @if ($client->practical_lessons_permission == 1)
                        <span style="color: green">&#10004;</span>
                    @else
                        <span style="color: red">&#10060;</span>
                    @endif
                </p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Mokyklinis teorijos įvertis</span>
                <p>
                    @if (is_numeric($client->theory_grade))
                        {{ $client->theory_grade }}
                    @else
                        --
                    @endif
                </p>
            </div>
        </div>

        <h5 style="font-weight: bold;">Veiksmai</h5>
        <div class="row">
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.edit', $client->id) }}" class="btn btn-warning btn-sm btnResize">Redaguoti
                    asmens duomenis</a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.end', $client->id) }}"
                    class="btn btn-danger btn-sm btnResize @if ($client->currently_studying != 1) disabled @endif">Užbaigti
                    mokymą</a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.practice', $client->id) }}"
                    class="btn @if ($client->practical_lessons_permission != 1) btn-secondary @else btn-danger @endif btn-sm btnResize
                    @if ($client->currently_studying != 1) disabled @endif">

                    @if ($client->practical_lessons_permission != 1)
                        Leisti praktiką
                    @else
                        Uždrausti praktiką
                    @endif
                </a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.insert.payment', $client->id) }}"
                    class="btn btn-secondary btn-sm btnResize @if ($client->currently_studying != 1) disabled @endif">Priimti
                    apmokėjimą</a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.insert.grade', $client->id) }}"
                    class="btn btn-secondary btn-sm btnResize
                    @if ($client->currently_studying != 1) disabled @endif">
                    Įvesti mokyklinės teorijos įvertį</a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.driveForm', $client->id) }}"
                    class="btn btn-secondary btn-sm btnResize
                    @if ($client->currently_studying != 1 || $client->practical_lessons_permission != 1) disabled @endif">
                    Įšrašyti mokymo lapą</a>
            </div>
        </div>
    </div>

    <script>
        var buttons = document.querySelectorAll('.btnResize');
        var maxWidth = 0;
        var maxHeight = 0;
        buttons.forEach(function(button) {
            var rect = button.getBoundingClientRect();
            var area = rect.width * rect.height;
            if (area > maxWidth * maxHeight) {
                console.log(area)
                maxWidth = rect.width;
                maxHeight = rect.height;
            }
        });
        buttons.forEach(function(button) {
            button.style.width = maxWidth + 'px';
            button.style.height = maxHeight + 'px';
        });
    </script>
@endsection
