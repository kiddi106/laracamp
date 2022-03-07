<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">

<form method="POST" action="{{ route('er.jo.employeement.update') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>
        
                <div class="col-md-10">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $employee->name }}" required autocomplete="name" autofocus>
        
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        
            <div class="form-group row">
                <label for="empl_id" class="col-md-2 col-form-label text-md-right">Employee ID</label>
        
                <div class="col-md-10">
                    <input id="empl_id" type="text" class="form-control @error('empl_id') is-invalid @enderror" name="empl_id" value="{{ $employee->empl_id }}" required autofocus>
        
                    @error('empl_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="display_name" class="col-md-2 col-form-label text-md-right">Bank Account</label>
        
                <div class="col-md-10">
                    <input id="bank_account" type="text" class="form-control @error('bank_account') is-invalid @enderror" name="bank_account" value="{{ $employee->bank_account }}" required autofocus>
        
                    @error('bank_account')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>                

            <div class="form-group row">
                <label for="display_name" class="col-md-2 col-form-label text-md-right">NPWP</label>
        
                <div class="col-md-10">
                    <input id="npwp" type="text" class="form-control @error('npwp') is-invalid @enderror" name="npwp" value="{{ $employee->npwp }}" required autofocus>
        
                    @error('npwp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>                

            <div class="form-group row">
                <label for="display_name" class="col-md-2 col-form-label text-md-right">Join Date</label>
        
                <div class="col-md-10">
                    <div class="input-group">
                        @php
                            $date = $employee->join_date;
                            if ($date) {
                                $date = explode('-', $date);
                                $date_full = $date[2].'-'.$date[1].'-'.$date[0];
                            } else {
                                $date_full = "";
                            }
                        @endphp
                        <input type="text" class="form-control datepicker" name="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask="" im-insert="false" placeholder="dd-mm-yyyy" value="{{ $date_full }}">
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