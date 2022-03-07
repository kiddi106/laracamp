<form method="POST" action="{{ route('permission.leave.store') }}" id="form" enctype="multipart/form-data">
    @csrf

    <div class="form-group row">
        <label for="shift_cd" class="col-md-4 col-form-label text-md-right">Permission Type</label>

        <div class="col-md-6">
            <select class="form-control" name="type" id="type">
                @foreach ($type as $item)
                    <option value="{{ $item->type_permission_cd }}">{{ $item->type_permission_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="req_date" class="col-md-4 col-form-label text-md-right">Request Date</label>

        <div class="col-md-3">
            <input id="req_date" type="text" class="form-control" name="req_date" value="{{ date("d/m/Y") }}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="permission_date" class="col-md-4 col-form-label text-md-right">Permission Date</label>

        <div class="col-md-6">

            <div class="input-group">
                <div class="date">
                <input type="text" class="form-control date" id="permission_date" name="permission_date" style="display: none">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row" id="upload" style="display: none">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right">File</label>

        <div class="col-md-6">
            <input type="file" name="file" id="file" accept="application/pdf">
        </div>
    </div>

    <div class="form-group row">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right">Note</label>

        <div class="col-md-6">
            <textarea class="form-control" name="note" id="note"></textarea>
        </div>
    </div>

</form>
<script>
var dateDisabled ={!! $date !!};

$(function(){
    $('.date').datepicker(
    {
        format: 'dd/mm/yyyy',
        multidate:true,
        // startDate: new Date(),
        beforeShowDay: function(date){
            dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            if(dateDisabled.indexOf(dmy) != -1){
                return false;
            }
            else{
                return true;
            }
        }
    });
});
</script>
