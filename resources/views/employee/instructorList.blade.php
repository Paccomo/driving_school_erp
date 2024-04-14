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
        <h1 style="margin-right: 0.4cm">Instruktoriai</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('register', ['employee']) }}"
                class="btn btn-primary btn-sm h-25 fs-6">Pridėti Darbuotoją</a>
        @endif
    </div>
    <div class="row list-card-row">
        @foreach ($employees as $employee)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card list-card">
                    <img class="card-img-top" src="{{ $employee->image }}"
                        style="height: 200px; width: 100%; object-fit: cover;" alt="Instruktorius">
                    <div class="card-body">
                        <h5 class="card-title">{{ $employee->person->name . " " . $employee->person->surname }}</h5>
                        <br>

                        <h5 class="card-title">Filialas</h5>
                        <p class="card-text">{{ $employee->branchAddress }}</p>

                        <h5 class="card-title">Tel. nr.</h5>
                        <p class="card-text">{{ $employee->phoneNum }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        cardHeightEquazile()
    </script>
@endsection
