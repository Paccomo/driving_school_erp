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
        <h1 style="margin-right: 0.4cm">Naudinga informacija</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('link.add') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti
                informacinę nuorodą</a>
        @endif
    </div>

    <div class="list-group">
        @foreach ($links as $link)
            <div style="display: inline-flex; align-items: center; margin-right: 10px;">
                <a href="{{ $link->link->link }}" target="_blank" onmouseover="this.style.color='blue';"
                    onmouseout="this.style.color='#333';"
                    class="list-group-item list-group-item-action">{{ $link->link->title }}</a>
                @if (Auth()->check() && Auth::user()->role == $roleDirector)
                    <a style="margin-left: 0.4cm; margin-right: 0.4cm;" href="{{ route('link.edit', $link->id) }}"
                        class="btn btn-warning btn-sm">Redaguoti</a>
                    <form action="{{ route('link.destroy', $link->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Ar tikrai norite pašalinti nuorodą {{ $link->link->title }}?')">Pašalinti</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection