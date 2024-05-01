@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Vairavimo pamokų vertinimai') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('lesson.grades.save') }}">
                            @csrf

                            @foreach ($lessons as $l)
                                <div class="row mb-3">
                                    <label for="lesson"
                                        class="col-md-3 col-form-label align-self-end text-md-end">{{ $l->client->person->name }}
                                        {{ $l->client->person->surname }}. {{ $l->date }}</label>

                                    <div class="col-md-3">
                                        <label for="noShow">Mokinys neatvyko</label>
                                        <div class="form-check form-switch">
                                            <input style="transform: scale(1.5); margin-top: 13px; margin-left: 13px;" id="noShow" type="checkbox" value="true" class="form-check-input"
                                                role="switch" @error('noShow') class="is-invalid @enderror" name="noShow{{$l->id}}">
                                        </div>


                                        @error('noShow')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="grade">Įvertis</label>
                                        <select id="grade" name="grade{{$l->id}}"
                                            class="form-control @error('grade') is-invalid @enderror">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>

                                        @error('grade')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Išsaugoti') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
