<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $model->awb }}.pdf</title>
    <style>
        @font-face {
            font-family: "Muli";
            src: url("{{ storage_path('fonts/Muli/Muli-Medium.ttf') }}") format('truetype');
        }
        @page { margin: 1cm; font-family: "Muli"}
        body{
            font-family: 'Muli';
            font-size: 8pt;
            line-height: 1;
        }
    </style>
</head>
@php
    $blns = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'October', 'November', 'Desember'];
    $time = strtotime($model->delivery->do_date);
    $tgl = date('d', $time) . ' ' . $blns[(int) date('m')] . ' ' . date('Y');
@endphp
<body>
    <div style="padding: .3cm; border-style: solid; width: 9.6cm">
        <div style="text-align: right;">
            <img src="{{ public_path('images/logo/logo-orbit.png') }}" alt="" style="height: .7cm; float: left">
            {{ $tgl }}
        </div>
        <div style="color: #404040; margin-top: .6cm">Delivery Tracking Number</div>
        <div style="font-size: 13pt;">{{ $model->awb }}</div>

        <table style="margin-top: .2cm; width: 100%;">
            <tbody>
                <tr style="color: #404040">
                    <td>Invoice Number</td>
                    <td>Customer Number</td>
                </tr>
                <tr style="font-size: 12pt;">
                    <td>{{ $model->document_number }}</td>
                    <td>{{ $model->customer_number }}</td>
                </tr>
            </tbody>
        </table>

        <table style="margin-top: .2cm; width: 100%;">
            <tbody>
                <tr style="color: #404040">
                    <td>To</td>
                </tr>
                <tr style="font-size: 12pt;">
                    <td>
                        {{ $model->receiver->name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $model->receiver->destination }}
                    </td>
                </tr>
            </tbody>
        </table>

        @php
            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        @endphp
        <br><br><br>
        <img src="data:image/png;base64,{{ base64_encode($generator->getBarcode($model->awb, $generator::TYPE_CODE_128)) }}" style="max-height: 1cm; max-width: 9cm;">
        <br>
        {{ $model->awb }} - {{ $model->delivery->type }}
    </div>
</body>
</html>
