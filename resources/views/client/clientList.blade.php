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
        <h1 style="margin-right: 0.4cm">Mokiniai</h1>
        <a style="margin-right: 1rem;" href="{{ route('register') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti
            mokinį</a>
        <a style="margin-right: 1rem;" href="{{ route('client.all') }}" class="btn btn-secondary btn-sm h-25 fs-6">Rodyti
            visus mokinius</a>
    </div>

    <form action="{{ route('client.find') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Mokinių paieška">
            <button type="submit" class="btn btn-outline-secondary">Ieškoti</button>
        </div>
    </form>

    <ul class="list-group list-group-flush">
        @foreach ($clients as $client)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('client.index', $client->id) }}"
                    style="text-decoration: none; color: black; padding: 4px; border-radius: 5px;"
                    onmouseover="this.style.backgroundColor='rgba(108, 117, 125, 0.6)';"
                    onmouseout="this.style.backgroundColor='';">
                    {{ $client->person->name }} {{ $client->person->name }}
                    @if (Auth::user()->role == $roleDirector)
                        ({{ $client->branch->address }})
                    @endif
                </a>
                <div>
                    <a href="{{ route('client.end', $client->id) }}" class="btn btn-danger btn-sm mr-2">Užbaigti mokymą</a>
                    <a href="{{ route('client.edit', $client->id) }}" class="btn btn-warning btn-sm mr-2">Redaguoti asmens
                        duomenis</a>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="pagination justify-content-center">
        {{ $clients->links() }}
    </div>
@endsection
