<table>
    <thead>
        <tr>
            <th>
                <h1>IMEI Not Found</h1>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($imeiNotFounds as $item)
        <tr>
            <td>{{ $item->imei }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr>

<table>
    <thead>
        <tr>
            <th>
                <h1>MSISDN Not Found</h1>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($msisdnNotFounds as $item)
        <tr>
            <td>{{ $item->msisdn }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
