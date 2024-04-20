@extends('layouts.app')

@section('content')
    <div style="margin: 0.5cm">
        <div class="card">
            <h5 class="card-header text-white bg-secondary">{{ $description->title }}</h5>
            <div class="card-body">
                <p class="card-text">{{ $description->description }}</p>
                <a href="{{ route('description.edit', $description->id) }}"
                    class="card-link btn btn-warning btn-sm">Redaguoti</a>
                <form action="{{ route('description.destroy', $description->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Ar tikrai norite pašalinti kurso aprašymą &quot;{{ $description->title }}&quot;?')">Pašalinti</button>
                </form>
            </div>
        </div>
    </div>
@endsection
