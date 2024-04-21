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
        <h1 style="margin-right: 0.4cm">Teorijos skaidrės</h1>
        @if (Auth()->check() && Auth::user()->role == $roleDirector)
            <a style="margin-right: 1.5rem;" href="{{ route('slides.add') }}" class="btn btn-primary btn-sm h-25 fs-6">Pridėti
                skaidres</a>
        @endif
    </div>

    <div class="list-group list-group-flush">
        @foreach ($slides as $slide)
            <div style="display: inline-flex; align-items: center; margin-right: 10px;">
                <a class="list-group-item list-group-item-action" href="{{ route('slides.index', [$slide->id]) }}"
                    onmouseover="this.style.backgroundColor='rgba(108, 117, 125, 0.6)';"
                    onmouseout="this.style.backgroundColor='';" target="_blank">
                    {{ $slide->order }}. {{ $slide->link->title }}
                </a>
                @if (Auth()->check() && Auth::user()->role == $roleDirector)
                    <a style="margin-left: 0.4cm; margin-right: 0.4cm;" href="{{ route('slides.edit', $slide->id) }}"
                        class="btn btn-warning btn-sm mr-4">Redaguoti</a>
                    <form action="{{ route('slides.destroy', $slide->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Ar tikrai norite pašalinti skaidres &quot;{{ $slide->link->title }}&quot;?')">Pašalinti</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
@endsection
