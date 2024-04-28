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
    <div style="margin: 0.5cm" class="row">
        <div class="col" style="border-right: 1px solid #000;">
            <div class="jumbotron">
                <h3 class="display-6">
                    Vairuotojo sveikatos pažyma
                </h3>
                <hr class="my-4">
                @if ($medCert != null)
                    <p>Galioja iki: {{ $medCert->valid_until }}</p>
                    <a href="{{ route('documents.download', [$medCert->id]) }}" target="_blank"
                        class="btn btn-secondary">Atsisiųsti <i class="fa-solid fa-download"></i></a>
                @else
                    <p>Pateikti sveikatos pažymą</p>
                    <a href="{{ route('documents.addMed') }}" class="btn btn-secondary">Pateikti</a>
                @endif
            </div>
        </div>
        <div class="col">
            <div class="jumbotron">
                <h3 class="display-6">
                    Pažyma apie išlaikytą teorijos egzaminą VĮ "REGITRA"
                </h3>
                <hr class="my-4">
                @if ($theory != null)
                    <p>Galioja iki: {{ $theory->valid_until }}</p>
                    <a href="{{ route('documents.download', [$theory->id]) }}" target="_blank"
                        class="btn btn-secondary">Atsisiųsti <i class="fa-solid fa-download"></i></a>
                @else
                    <p>Pateikti pažymą apie išlaikytą valstybinį teorijos egzaminą</p>
                    <a href="{{ route('documents.addTheory') }}" class="btn btn-secondary">Pateikti</a>
                @endif
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            var headings = document.querySelectorAll('.display-6');
            var maxHeight = 0;

            headings.forEach(function(heading) {
                maxHeight = Math.max(maxHeight, heading.offsetHeight);
            });

            headings.forEach(function(heading) {
                heading.style.height = maxHeight + 'px';
            });
        });
    </script>
@endsection
