@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Pagrindinis puslapis') }}</div>

                    <div class="card-body">
                        <img src="{{ asset('storage/M.jpg') }}" class="card-img-top" alt="Vairavimo mokykla">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
