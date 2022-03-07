<table id="datatable" class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th>Date</th>
            <th>Select Shift</th>
            <th>Current Shift</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dateRange as $key => $item)
            <tr>
                <td id="tanggal-{{ $key }}">{{ $item['tanggal'] }}</td>
                <td>
                    @foreach ($shifts as $shift)
                    <button class="btn btn-primary btn-xs" id="shift-{{ $shift->shift_cd }}-{{ $shift->shift_nm}}" onclick="setShift({{ $key }},'{{ $shift->shift_cd }}')">{{ $shift->shift_nm }}</button>
                    @endforeach
                    <button class="btn btn-primary btn-xs" id="shift-off-off" onclick="setShift({{ $key }},'off')">OFF</button>
                </td>
                <td> {{ $item['shift'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
{{-- <script src=" {{ asset('/js/sweetalert2.all.min.js') }}"></script> --}}
<script>
    $.getScript( "{{ asset('/js/sweetalert2.all.min.js') }}" );
    function setShift(index,code) {
        var tanggal = $('#tanggal-'+index).html();
        var emp = $('#employee').val();

        var department = $('#department').val()
        var role = $('#role').val()
            var date = $('#date').val();
        $.ajax({
            url : '{{ route("mst.shift.setShift") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'html',
            data: {
                tanggal:tanggal,
                employee_uuid:emp,
                shift_cd:code
            },
            success: (data) => {
                data = $.trim(data);
                if (data === 'success') {
                    $.ajax({
                        url : '{{ route("mst.shift.dateRange") }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType : 'html',
                        data: {
                            date:date,
                            emp:emp
                        },
                        success: (data) => {
                            $("#tableSet").html(data)
                        },
                        error: (xhr) => {
                            console.log(xhr);
                        }
                    });
                } else if (data === 'already') {
                    alert('Data Already Set')
                } else {
                    alert('Something Wrong')
                }

                // $("#tableSet").html(data)
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    }
</script>