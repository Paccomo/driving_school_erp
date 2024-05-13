@extends('layouts.app')

@section('content')
    @if (session('fail'))
        <div class="alert alert-danger auto-dismiss">
            {{ session('fail') }}
        </div>
    @endif

    <div style="padding: 0.2cm 0.4cm 1cm 0.4cm" class="d-flex align-items-left">
        <h1 style="margin-right: 0.4cm">Vairavimo pamokų rezervacija</h1>
    </div>

    <div style="margin-left: 0.5cm" id="calendar"></div>

    <script>
        window.calendarEvents = @json($events);
    </script>

    <script>
        function handleEventClick(info) {
            var start = new Date(info.event.start);
            var year = start.getFullYear();
            var month = ('0' + (start.getMonth() + 1)).slice(-2);
            var day = ('0' + start.getDate()).slice(-2);
            var hours = ('0' + start.getHours()).slice(-2);
            var minutes = ('0' + start.getMinutes()).slice(-2);
            var seconds = ('0' + start.getSeconds()).slice(-2);

            start = [year, month, day].join('-') + ' ' + [hours, minutes, seconds].join(':');
            var confirmation = confirm('Ar tikrai norite rezervuoti laiką ' + start + '?');
            console.log("beforeconf")

            if (confirmation) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('lesson.reservation.save') }}';

                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'start';
                input.value = start;
                form.appendChild(input);
                document.body.appendChild(form);
                console.log("beforesub")
                form.submit()
            }
        }
    </script>

    @vite('resources/js/app.js')
@endsection
