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

        <div class="row">
            <div class="col">
                <h5 style="font-weight: bold;">Instruktorius: {{ $instructor }}</h5>
                <form method="POST" action="{{ route('client.instructor') }}" class="row align-items-center">
                    @csrf
                    <input type="hidden" name="client" value="{{ $client->id }}" />
                    <div class="col-auto">
                        <select name="inst" id="instSelect" class="form-select" required>
                            @foreach ($allInstructors as $inst)
                                <option @if ($client->fk_instructor == $inst->id) selected @endif value="{{ $inst->id }}">
                                    {{ $inst->person->name . ' ' . $inst->person->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-secondary">Pakeisti instruktorių</button>
                    </div>
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
                <span style="font-weight: bold">Liko sumokėti:</span>
                <p>{{ $client->to_pay }}€</p>
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

        @if ($grades->isNotEmpty())
            <h5 style="margin-top: 0.5cm; font-weight: bold;">Pažymiai</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col">Įvertinimas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $g)
                        <tr>
                            <td>{{ $g->date }}</td>
                            <td>{{ $g->grade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h5 style="font-weight: bold;">Veiksmai</h5>
        <div class="row">
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('client.edit', $client->id) }}" class="btn btn-warning btn-sm btnResize">Redaguoti
                    asmens duomenis</a>
            </div>
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('pw.form', $client->id) }}"
                    class="btn btn-danger btn-sm btnResize @if ($client->currently_studying != 1) disabled @endif">Pakeisti
                    slaptažodį</a>
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
        @if ($isCat)
        <div style="margin-top: 0.5cm" class="row">
            <form method="POST" action="{{ route('client.lessons.add') }}" class="row align-items-center">
                @csrf
                <input type="hidden" name="client" value="{{ $client->id }}" />
                <div class="col-auto">
                    <label for="amount" class="form-label">Papildomų vairavimų kiekis</label>
                </div>
                <div class="col-auto">
                    <input type="number" id="amount" name="amount" class="form-control" min="1"
                        step="1">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-secondary">Pridėti pamokas</button>
                </div>
            </form>
        </div>
        @endif

        @if ($contracts->isNotEmpty())
            <h5 style="margin-top: 0.5cm; font-weight: bold;">Sutartys</h5>
            <div class="row">
                @foreach ($contracts as $c)
                    <div class="col">
                        <a href="{{ route('contract.download', [$c->id]) }}" target="_blank" class="btn btn-secondary">
                            @if ($c->contractRequest != null)
                                @lang('messages.' . $c->contractRequest->type) <i class="fa-solid fa-download"></i>
                            @else
                                {{ $c->name }} <i class="fa-solid fa-download"></i>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($documents->isNotEmpty())
            <h5 style="margin-top: 0.5cm; font-weight: bold;">Dokumentai</h5>
            @foreach ($documents as $d)
                <div style="margin-top: 0.5cm" class="row">
                    <div class="col">
                        <a href="{{ route('documents.download', [$d->id]) }}" target="_blank" class="btn btn-secondary">
                            @lang('messages.' . $d->type) <i class="fa-solid fa-download"></i>
                        </a>
                        <a href="{{ route('documents.destroy', [$d->id]) }}" target="_blank" class="btn btn-danger">
                            Pašalinti dokumentą
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
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
