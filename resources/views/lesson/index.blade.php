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
        <h1 style="margin-right: 0.4cm">Vairavimo pamokos</h1>
        <a class="btn btn-secondary @if((int)$self->lessons <= 0) disabled @endif" href="{{ route('lesson.reservation') }}">
            Rezervuoti laiką
        </a>
    </div>

    @if ($self->practical_lessons_permission != 1)
        <div class="alert alert-danger">
            <h3>Vairavimo pamokos dar nėra leidžiamos</h3>
            <p>Jums šiuo metu nėra galimybės rezervuoti vairavimo pamokų. Jei manote, kad tai yra klaida - susisiekite su
                savo lankomo filialo adminstracija.</p>
        </div>
    @else
        <div class="row align-items-center">
            <div class="col-auto">
                <h5 style="font-weight: bold;">Pasirinkti instruktorių:</h5>
            </div>
            <div class="col-auto">
                <form method="POST" action="{{ route('lesson.instructor') }}">
                    @csrf
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <select name="inst" id="instSelect" class="form-select" required {{ $self->fk_instructor ? 'disabled' : '' }}>
                                @foreach ($allInstructors as $inst)
                                    <option value="{{ $inst->id }}">
                                        {{ $inst->person->name . ' ' . $inst->person->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-secondary {{ $self->fk_instructor ? 'disabled' : '' }}">Pasirinkti</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($lessons->isNotEmpty())
            <h5 style="font-weight: bold; margin-top: 1cm;">Būsimos pamokos:</h5>
            <ul class="list-group list-group-flush">
                @foreach ($lessons as $l)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            Data: <span style="font-weight: bold;">{{ $l->date }}</span>.
                            Valanda: <span style="font-weight: bold;">{{ $l->time }}</span>
                        </div>
                        <div>
                            <a href="{{ route('lesson.cancel', $l->id) }}"
                                class="btn btn-danger btn-sm mr-2 @if (strtotime(date('Y-m-d')) >= strtotime($l->cancelDate)) disabled @endif">
                                Atšaukti
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif
@endsection
