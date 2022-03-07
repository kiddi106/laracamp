<br>
    <!-- date picker -->
<form method="POST" action="">
    @csrf

    <div class="form-group row">
        <label for="shift_cd" class="col-md-2 col-form-label">Permission Type</label>

        <div class="col-md-6">
            <label for="" class="col-form-label">{{ $permission[0]->type_permission_name }}</label>
        </div>
    </div>

    <div class="form-group row">
        <label for="req_date" class="col-md-2 col-form-label">Request Date</label>

        <div class="col-md-6">
            <label for="" class="col-md-4 col-form-label">{{ $permission[0]->req_date }}</label>
        </div>
    </div>

    <div class="form-group row">
        <label for="permission_date" class="col-md-2 col-form-label">Permission Date</label>

        <div class="col-md-6">
            <ul>
                @foreach ($dtl as $item)
                    <li>{{ $item->permission_date }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="form-group row">
        <label for="sched_out" class="col-md-2 col-form-label">Note</label>

        <div class="col-md-6">
            <label for="" class="col-md-12 col-form-label">{{ $permission[0]->note }}</label>
        </div>
    </div>

    @if ($permission[0]->status_id == 3)
    <div class="form-group row">
        <label for="sched_out" class="col-md-2 col-form-label">Cancel Reason</label>

        <div class="col-md-6">
            <label for="" class="col-md-12 col-form-label">{{ $permission[0]->cancel_reason }}</label>
        </div>
    </div>
    @endif

    @if ($permission[0]->status_id == 4)
    <div class="form-group row">
        <label for="sched_out" class="col-md-2 col-form-label">Reject Reason</label>

        <div class="col-md-6">
            <label for="" class="col-md-12 col-form-label">{{ $permission[0]->reject_reason }}</label>
        </div>
    </div>
    @endif

    @if ($permission[0]->file_upload)
    <div class="form-group row">
        <label for="sched_out" class="col-md-2 col-form-label">File Upload</label>

        <div class="col-md-6">
            <a href="{{ route('permission.sick.downloadFile',['name'=> $permission[0]->file_upload]) }}">Download File</a>
        </div>
    </div>
    @endif

</form> 
<script>
$('#modal-btn-save').hide()
</script>
