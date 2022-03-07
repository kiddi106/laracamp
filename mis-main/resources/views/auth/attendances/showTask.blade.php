<form method="POST" id="form">
    @csrf
<br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-sm" id="table-task">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Task</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>    
</form>

<script>
$('#table-task').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('attendance.task.show',[$id]) }}",
                type: 'get'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'note', name: 'note' },
                { data: 'progress', name: 'progress' },
            ]
        });
</script>