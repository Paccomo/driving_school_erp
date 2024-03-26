<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <h1 style="margin-left: 20px; text-align: center;">Naudotojas: {{ $name }} {{ $surname }}</h1>
    <br>
    <p style="margin-left: 30px; font-size: 0.5cm">Prisijungimo informacija:</p>
    <table style="margin-left: auto; margin-right: auto;" class="horizontal-table">
        <tr style="border-bottom: 1px solid black;">
            <td style="font-size: 16px;">El. paštas:</td>
            <td style="font-size: 16px;">{{ $email }}</td>
        </tr>
        <tr>
            <td style="font-size: 16px;">Slaptžodis:</td>
            <td style="font-size: 16px;">{{ $pw }}</td>
        </tr>
    </table>
</body>

</html>
