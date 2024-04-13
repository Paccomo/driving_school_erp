@extends('layouts.app')

@section('content')
    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Filialai</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('branch.add') }}"
                class="btn btn-primary btn-sm h-25 fs-6">Pridėti filialą</a>
        @endif
    </div>
    <div class="row list-card-row">
        @foreach ($branches as $branch)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('branch.index', ['id' => $branch->id]) }}" style="text-decoration: none">
                    <div class="card list-card">
                        <img class="card-img-top" src="{{ $branch->image }}"
                            style="height: 200px; width: 100%; object-fit: cover;" alt="Filialo nuotrauka">
                        <div class="card-body">
                            <p class="card-text">{{ $branch->address }}</p>
                            @if ($branch->phone_number != null)
                                <p class="card-text">Tel: {{ $branch->phone_number }}</p>
                            @endif
                            @if ($branch->email != null)
                                <p class="card-text">{{ $branch->email }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <script>
        cardHeightEquazile()
    </script>
@endsection
