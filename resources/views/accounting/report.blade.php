@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="alert alert-success auto-dismiss">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Mėnesio finansinė apskaita</h1>
    </div>

    <div class="row align-items-center">
        <div class="col-auto">
            <h5 style="font-weight: bold;">Pasirinkti mėnesį</h5>
        </div>
        <div class="col-auto">
            <select id="month" name="month">
                @foreach ($options as $monthYear)
                    <option value="{{ $monthYear->year }}-{{ str_pad($monthYear->month, 2, '0', STR_PAD_LEFT) }}">
                        {{ $monthYear->year }}-{{ str_pad($monthYear->month, 2, '0', STR_PAD_LEFT) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <a id="navigateLink" class="btn btn-outline-secondary" href="#">Pasirinkti</a>
        </div>
    </div>

    <h2 style="margin-top: 1cm">{{ $month }}</h2>

    <div class="row">
        <div class="col">
            <span style="font-weight: bold;">Mėnesio pajamos:</span>
            <p class="custom-paragraph">{{ $income }}€</p>
        </div>
        <div class="col">
            <span style="font-weight: bold;">Mėnesio išlaidos:</span>
            <p class="custom-paragraph">{{ $expense }}€</p>
        </div>
        <div class="col">
            <span style="font-weight: bold;">Pelnas:</span>
            <p class="custom-paragraph">{{ $diff }}€</p>
        </div>
    </div>

    @foreach ($types as $key => $t)
        <h5 style="margin-top: 0.5cm; font-weight: bold;">{{ $t }}</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data</th>
                    <th scope="col">Suma</th>
                    <th scope="col">Priežastis</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach ($values[$key] as $v)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>
                            @if ($v->paid_at != null)
                                {{ $v->paid_at }}
                            @else
                                {{ $v->received_at }}
                            @endif
                        </td>
                        <td>{{ $v->amount }}€</td>
                        <td>{{ $v->reason }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <style>
        .custom-paragraph {
            font-size: 1.17em;
        }
    </style>

    <script>
        document.getElementById('navigateLink').addEventListener('click', function() {
            var selectedMonth = document.getElementById('month').value;
            var url = "{{ route('accounting.report', ':month') }}";
            url = url.replace(':month', selectedMonth);
            window.location.href = url;
        });
    </script>
@endsection
