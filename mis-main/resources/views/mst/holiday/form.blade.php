<form action="{{ route('mst.holiday.store') }}" method="POST">
    @csrf
    @if ($model->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="purpose" class="col-md-3 col-form-label text-md-right">Date</label>
                        <div class="col-md-8">
                           <input class="form-control" type="text" id="_req_start" name="date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="purpose" class="col-md-3 col-form-label text-md-right">Type</label>
                        <div class="col-md-8">
                           <select class="form-control select2" name="type">
                               <option value="HLDAY">Holiday</option>
                               <option value="MAS-L">Mass Leave</option>
                           </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="purpose" class="col-md-3 col-form-label text-md-right">Notes</label>
                        <div class="col-md-8">
                           <textarea name="notes" id="notes" cols="30" class="form-control" style="width: 100%; font-size:14px"></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    $(function() {

    $("#_req_start").daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
            $('[name^="' + $(this).attr('id').replace('_', '') + '"]').val(picker.startDate.format('DD/MM/YYYY'));
        });
    });
</script>
