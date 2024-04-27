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
        <h1 style="margin-right: 0.4cm">Sutarčių užklausos</h1>
        <a style="margin-right: 1rem;" href="{{ route('contract.all') }}" class="btn btn-secondary btn-sm h-25 fs-6">Visos užklausos</a>
        <a style="margin-right: 1rem;" href="{{ route('contract.accepted') }}" class="btn btn-secondary btn-sm h-25 fs-6">Patvirtintos užklausos</a>
        <a style="margin-right: 1rem;" href="{{ route('contract.denied') }}" class="btn btn-secondary btn-sm h-25 fs-6">Atmestos užklausos</a>
    </div>

    <ul class="list-group list-group-flush">
        @foreach ($contracts as $contract)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('contract.index', $contract->id) }}"
                    style="text-decoration: none; color: black; padding: 4px; border-radius: 5px;"
                    onmouseover="this.style.backgroundColor='rgba(108, 117, 125, 0.6)';"
                    onmouseout="this.style.backgroundColor='';">
                    @if ($contract->guestReq != null)
                        {{ $contract->guestReq->name . " " . $contract->guestReq->surname }}.
                    @else
                        {{ $contract->clientReq->client->person->name . " " . $contract->clientReq->client->person->surname }}.
                    @endif
                    {{ $contract->requested_on }} - {{ $types[$contract->type] }} <i class="fa-solid fa-arrow-right"></i>
                </a>
                <div>
                    @if ($contract->status == 'approved' && $contract->contract == null)
                        <span class="text-danger">Trūksta sutarties!</span>
                    @endif
                    @if ($contract->status == 'unconfirmed')
                        <span class="text-danger">Trūksta atsakymo!</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    <div class="pagination justify-content-center">
        {{ $contracts->links() }}
    </div>
@endsection