<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        hr {
            border: 1px solid #ccc;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Laba diena,</p>

        <p>{{ $reqType }}, kurią pateikėte {{ $date }} buvo patvirtinta.</p>

        <p>{{ $init }} bus pradėtas, jums atvykus į filialą. Filialo adresas - {{ $addr }}</p>

        @include('mails.autoMsg')

        <hr>

        @include('mails.footer')
    </div>
</body>
</html>
