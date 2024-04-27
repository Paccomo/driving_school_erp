@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white bg-secondary">{{ __('Naujas prisijungimas') }}</div>

                    <div class="card-body">
                        <p>Naudotojas:</p>
                        <table class="table table-borderless horizontal-table">
                            <tr>
                                <td style="border-bottom: 1px solid black;">Vardas:</td>
                                <td style="border-bottom: 1px solid black;">{{ $name }}</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid black;">Pavardė:</td>
                                <td style="border-bottom: 1px solid black;">{{ $surname }}</td>
                            </tr>
                        </table>

                        <p>Prisijungimo informacija:</p>
                        <table class="table table-borderless horizontal-table">
                            <tr>
                                <td style="border-bottom: 1px solid black;">El. paštas:</td>
                                <td style="border-bottom: 1px solid black;">{{ $email }}</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid black;">Slaptžodis:</td>
                                <td data-original-text="{{ $pw }}" id="toggleCell" onclick="toggleVisibility('{{ $pw }}')" class="clickable" style="border-bottom: 1px solid black;">{{ $pw }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="no-print">
            <div class="row justify-content-center">
                <div class="col text-center">
                    <button onclick="window.print()" class="btn btn-outline-primary">Spausdinti</button>
                    <form id="downloadForm" action="{{ route('user.credentials.download') }}" method="post" style="display: inline;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="name" value="{{ $name }}">
                        <input type="hidden" name="surname" value="{{ $surname }}">
                        <input type="hidden" name="pw" value="{{ $pw }}">
                        <button type="submit" class="btn btn-outline-secondary">Atsisiųsti kaip PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        toggleVisibility('{{ $pw }}');
    </script>
@endsection
