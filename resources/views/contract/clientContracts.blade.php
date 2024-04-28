@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Mano sutartys</h1>
        <a style="margin-right: 1rem;" href="{{ route('contract.termination') }}"
            class="btn btn-secondary btn-sm h-25 fs-6 @if ($currentlyImprovement) disabled @endif">
            Nutraukti mokymosi sutartį
        </a>
        <a style="margin-right: 1rem;" href="{{ route('contract.extension') }}"
            class="btn btn-secondary btn-sm h-25 fs-6 @if ($currentlyImprovement) disabled @endif">
            Pratęsti mokymosi sutartį
        </a>
    </div>

    <ul class="list-group">
        @foreach ($contracts as $contract)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                @if ($contract->contractRequest != null)
                    {{ $types[$contract->contractRequest->type] }}
                @else
                    {{ $contract->name }}
                @endif
                <div>
                    <a target="_blank" href="{{ route('contract.download', $contract->id) }}"
                        class="btn btn-secondary">Atsisiųsti</a>
                </div>
            </li>
        @endforeach
    </ul>
@endsection
