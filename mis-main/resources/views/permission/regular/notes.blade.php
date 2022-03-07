<br>
    <!-- date picker -->
<form method="POST" action="{{ route('permission.regular.'.$type) }}">
    @csrf
    <div class="form-group row">
        <input type="hidden" name="permission_id" value="{{ $permission_id }}">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right">Notes</label>

        <div class="col-md-6">
            <textarea class="form-control" name="note" id="note"></textarea>
        </div>
    </div>

</form> 
<script>

$('#modal-btn-save').show()

</script>
