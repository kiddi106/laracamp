<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

<form method="POST" action="{{ route('er.jo.employeement.releaseProcess') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-10">           
            <div class="form-group row">
                <label for="display_name" class="col-md-2 col-form-label text-md-right">Resign Date</label>
        
                <div class="col-md-10">
                    <div class="input-group">
                        @php
                            $date = $employee->resign_date;
                            if ($date) {
                                $date = explode('-', $date);
                                $date_full = $date[2].'-'.$date[1].'-'.$date[0];
                            } else {
                                $date_full = "";
                            }
                        @endphp
                        <input type="text" class="form-control datepicker" name="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask="" im-insert="false" placeholder="dd-mm-yyyy" value="{{ $date_full }}" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                    @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>        
            <div class="form-group row">
                <label for="display_name" class="col-md-2 col-form-label text-md-right">Resign Reason</label>
        
                <div class="col-md-10">
                    <textarea id="resign_reason" class="form-control @error('resign_reason') is-invalid @enderror" name="resign_reason" required autofocus>
                        {{ $employee->resign_reason }}
                    </textarea>
        
                    @error('resign_reason')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>                        
        </div>
        <input id="id" type="hidden" class="form-control" name="id" value="{{ base64_encode($employee->uuid) }}" required autofocus>
    </div>    
</form> 

<!-- date picker -->
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    }).inputmask('dd-mm-yyyy', { 'placeholder': 'dd-mm-yyyy' })
</script>