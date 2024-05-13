@extends('layouts.app')

@section('content')
    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Būsimų vairavimo pamokų tvarkaraštis</h1>
    </div>

    <div style="margin-left: 0.5cm" id="calendar"></div>

    <script>
        window.calendarEvents = @json($events);
    </script>
    @vite('resources/js/app.js')

    <style>
        .fc-event {
            cursor: default !important;
        }
    </style>
@endsection
