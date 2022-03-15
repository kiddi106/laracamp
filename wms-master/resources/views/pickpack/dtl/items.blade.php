<table class="table table-sm table-bordered table-hover dt-bootstrap4">
    <thead>
        <tr>
            <th style="width: 30px;">No</th>
            <th>SSID</th>
            <th>IMEI</th>
            <th>MSISDN</th>
            {{-- <th style="width: 30px"></th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($model->items as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->orbitStock->router->ssid }}</td>
                <td>{{ $item->orbitStock->router->imei }}</td>
                <td>{{ $item->orbitStock->simcard->msisdn }}</td>
                {{-- <td><button type="button" class="btn btn-xs btn-default" onclick="editOrbitItem({{ $item->order_item_id }}, {{ $item->id }}, '{{ $item->orbitStock->router->ssid }}')"><i class="fas fa-eye"></i></button></td> --}}
                {{-- <td>
                    <button type="button" class="btn btn-xs btn-danger"></button>
                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>
