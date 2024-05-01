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
    <div style="margin: 0.5cm">
        <div style="margin-bottom: 1cm;">
            <div class="d-flex align-items-left">
                <h4 style="margin-right: 0.4cm">
                    {{ $types[$contract->type] }} -
                    @if ($contract->guestReq != null)
                        {{ $contract->guestReq->name . ' ' . $contract->guestReq->surname }}.
                    @else
                        {{ $contract->clientReq->client->person->name . ' ' . $contract->clientReq->client->person->surname }}.
                    @endif
                </h4>
            </div>
        </div>

        <h5 style="margin-top: 0.5cm; font-weight: bold;">Pateikusiojo informacija</h5>
        <div class="row">
            <div class="col">
                <span style="font-weight: bold">Vardas:</span>
                @if ($contract->guestReq != null)
                    {{ $contract->guestReq->name }}
                @else
                    {{ $contract->clientReq->client->person->name }}
                @endif
            </div>
            <div class="col">
                <span style="font-weight: bold">Pavardė:</span>
                @if ($contract->guestReq != null)
                    {{ $contract->guestReq->surname }}
                @else
                    {{ $contract->clientReq->client->person->surname }}
                @endif
            </div>
            <div class="col">
                <span style="font-weight: bold">El. paštas:</span>
                @if ($contract->guestReq != null)
                    {{ $contract->guestReq->email }}
                @else
                    {{ $contract->clientReq->client->account->email }}
                @endif
            </div>
            <div class="col">
                <span style="font-weight: bold">Tel. nr.:</span>
                @if ($contract->guestReq != null)
                    {{ $contract->guestReq->phone_number }}
                @else
                    {{ decrypt($contract->clientReq->client->person->phone_number) }}
                @endif
            </div>
        </div>

        <h5 style="margin-top: 1cm; font-weight: bold;">Užklausos informacija</h5>
        <div class="row">
            <div class="col">
                <span style="font-weight: bold">Filialas:</span>
                <p>{{ $branch->address }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Kursas:</span>
                <p>{{ $course->name }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Pateikta:</span>
                <p>{{ $contract->requested_on }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Tipas:</span>
                <p>{{ $types[$contract->type] }}</p>
            </div>
            <div class="col">
                <span style="font-weight: bold">Būsena:</span>
                <p>@lang('messages.' . $contract->status)</p>
            </div>
        </div>

        <h5 style="margin-top: 1cm; font-weight: bold;">Komentaras</h5>
        <div class="row">
            <pre>{{ $contract->comment }}</pre>
        </div>

        <h5 style="margin-top: 1cm; font-weight: bold;">Veiksmai</h5>
        <div class="row">
            <div class="col">
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('contract.approve', $contract->id) }}"
                    class="btn btn-success btn-sm btnResize @if($contract->status != 'unconfirmed') disabled @endif">
                    Patvirtinti
                </a>
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('contract.deny', $contract->id) }}"
                    class="btn btn-danger btn-sm btnResize @if($contract->status != 'unconfirmed') disabled @endif">
                    Atmesti
                </a>
                <a style="margin-right: 0.4cm; margin-bottom: 0.2cm; font-size: 14px"
                    href="{{ route('contract.add', $contract->id) }}"
                    class="btn btn-secondary btn-sm btnResize @if ($contract->status != 'approved' || $contract->contract != null) disabled @endif">
                    Pridėti sutartį
                </a>
            </div>
        </div>
    </div>

    <script>
        var buttons = document.querySelectorAll('.btnResize');
        var maxWidth = 0;
        var maxHeight = 0;
        buttons.forEach(function(button) {
            var rect = button.getBoundingClientRect();
            var area = rect.width * rect.height;
            if (area > maxWidth * maxHeight) {
                console.log(area)
                maxWidth = rect.width;
                maxHeight = rect.height;
            }
        });
        buttons.forEach(function(button) {
            button.style.width = maxWidth + 'px';
            button.style.height = maxHeight + 'px';
        });
    </script>
@endsection