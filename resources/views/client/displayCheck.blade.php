@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header text-white bg-secondary">{{ __('Apmokėjimas sėkmingai išsaugotas') }}</div>
                    <div class="card-body">
                        <p class="card-text">Mokinys: {{ $student }}</p>
                        <p class="card-text">Suma: {{ $amount }}</p>
                        <p class="card-text">Paskirtis: {{ $reason }}</p>
                        <p class="card-text">Data: {{ $date }}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="no-print">
                <div class="row justify-content-center">
                    <div class="col text-center">
                        <form id="downloadForm" action="{{ route('client.receipt') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="payId" value="{{ $payId }}">
                            <input type="hidden" name="reason" value="{{ $reason }}">
                            <input type="hidden" name="sum" value="{{ $amount }}">
                            <input type="hidden" name="sumW" value="{{ $sumW }}">
                            <input type="hidden" name="student" value="{{ $student }}">
                            <button type="submit" class="btn btn-outline-secondary">Atsisiųsti pinigų priėmimo
                                kvitą</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.querySelector('.btn-outline-secondary').addEventListener('click', function() {
                document.getElementById('downloadForm').submit();
            });
        </script>
    @endsection
