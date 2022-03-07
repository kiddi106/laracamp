<table id="datatable" class="table table-bordered table-hover table-sm">
    <thead>
        <tr>
            <th>Date</th>
            <th style="width:40%">Select Shift</th>
            <th style="width:30%">Select Location</th>
            <th style="width:15%">Current Shift</th>
            <th style="width:15%">Current Location</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dateRange as $key => $item)
            <tr>
                <td id="tanggal-{{ $key }}">{{ $item['tanggal'] }}</td>
                <td>
                    @foreach ($shifts as $shift)
                    <button class="btn btn-primary btn-xs" id="shift-{{ $shift->id }}-{{ $shift->shift_nm}}" onclick="setShift({{ $key }},{{ $shift->id }})">{{ $shift->shift_nm }}</button>
                    @endforeach
                    <button class="btn btn-danger btn-xs" id="shift-off-off" onclick="setShift({{ $key }},'off')">OFF</button>
                </td>
                <td>
                    @foreach ($locations as $loc)
                    <button class="btn btn-success btn-xs" id="loc-{{ $loc->id }}-{{ $loc->name}}" onclick="setLocation({{ $key }},{{ $loc->id }})">{{ $loc->name }}</button>
                    @endforeach
                    <button class="btn btn-danger btn-xs" id="loc-off-off" onclick="setLocation({{ $key }},'off')">OFF</button>
                </td>
                <td> {{ $item['shift'] }}</td>
                <td> {{ $item['location'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
$.getScript( "{{ asset('/js/sweetalert2.all.min.js') }}" );

function setShift(index,code) {
    var tanggal = $('#tanggal-'+index).html();
    var emp = $('#employee').val();

    var department = $('#department').val()
      var role = $('#role').val()
        var date = $('#date').val();
    $.ajax({
        url : '{{ route("er.project.shift.setShift") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType : 'html',
        data: {
            tanggal:tanggal,
            employee_uuid:emp,
            shift_cd:code,
            project_code:department,
        },
        success: (data) => {
            data = $.trim(data);
            if (data === 'success') {
                $.ajax({
                    url : '{{ route("er.project.shift.dateRange") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType : 'html',
                    data: {
                        date:date,
                        emp:emp,
                        department:department,
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

function setLocation(index,code) {
    var tanggal = $('#tanggal-'+index).html();
    var emp = $('#employee').val();

    var department = $('#department').val()
      var role = $('#role').val()
        var date = $('#date').val();
    $.ajax({
        url : '{{ route("er.project.shift.setShift") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType : 'html',
        data: {
            tanggal:tanggal,
            employee_uuid:emp,
            loc_cd:code,
            project_code:department,
        },
        success: (data) => {
            data = $.trim(data);
            if (data === 'success') {
                $.ajax({
                    url : '{{ route("er.project.shift.dateRange") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType : 'html',
                    data: {
                        date:date,
                        emp:emp,
                        department:department,
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