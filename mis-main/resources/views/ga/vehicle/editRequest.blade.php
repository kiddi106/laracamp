<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<!-- date picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<form method="POST" action="{{ route('ga.vehicle.update') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row" style="margin-top:10px; margin-bottom:unset;">
                <label for="req_time" class="col-md-4 col-form-label text-md-right">{{ __('Req Time') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="req_vehicle_id" id="req_vehicle_id" value="{{ base64_encode($reqVehicle->req_vehicle_id) }}">
                    <input type="hidden" name="empl_id" id="empl_id" value="{{ base64_encode(Auth::user()->uuid) }}">
                    <input type="hidden" name="req_time" id="req_time" value="{{ date('Y-m-d H:i:s') }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ date('D, d M Y H:i:s', time()) }}</label>
                    @error('req_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="ext" class="col-md-4 col-form-label text-md-right">{{ __('Ext') }}</label>
        
                <div class="col-md-6">
                    <label class=" col-form-label" style="font-weight:normal">{{ $empl->ext_no }}</label>
                    @error('ext')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="departement" class="col-md-4 col-form-label text-md-right">{{ __('Departement') }}</label>
        
                <div class="col-md-6">
                    <label class=" col-form-label" style="font-weight:normal">{{ $dept }}</label>
                    @error('departement')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="req_name" class="col-md-4 col-form-label text-md-right">{{ __('Req Name') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="req_name" id="req_name" value="{{ Auth::user()->uuid }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ $empl->name }}</label>
                    @error('req_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="purpose" class="col-md-4 col-form-label text-md-right">{{ __('Purpose') }}</label>
        
                <div class="col-md-6">
                    <textarea class="form-control" name="purpose" id="purpose">{{$reqVehicle->purpose}}</textarea>
                    @error('purpose')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="destination" class="col-md-4 col-form-label text-md-right">{{ __('Destination') }}</label>
        
                <div class="col-md-6">
                    <textarea class="form-control" name="destination" id="destination">{{$reqVehicle->destination}}</textarea>
                    @error('destination')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
        
                <div class="col-md-3">
                    <div class="input-group">
                        @php
                            $date = $reqVehicle->req_date;
                            $date = explode('-', $date);
                            $date_full = $date[2].'/'.$date[1].'/'.$date[0];
                        @endphp
                        <input type="text" class="form-control datepicker" name="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" placeholder="dd/mm/yyyy" value="{{$date_full}}">
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
                <label for="departure_time" class="col-md-4 col-form-label text-md-right">{{ __('Departure Time') }}</label>
        
                <div class="col-md-2">
                    <div class="input-group">
                        @php
                            $departure_tm = ($reqVehicle->departure_tm != '') ? date_create($reqVehicle->departure_tm) : '';
                        @endphp
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                        </div>
                    <input type="text" class="form-control time_vehicle" name="departure_time" id="departure_time" placeholder="HH:MM" value="{{ ($departure_tm != '') ? date_format($departure_tm,"H:i") : '' }}">
                    </div>
                    @error('departure_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="arrives_time" class="col-md-4 col-form-label text-md-right">{{ __('Pick up Time') }}</label>
        
                <div class="col-md-2">
                    <div class="input-group">
                        @php
                            $arrives_tm = ($reqVehicle->arrives_tm != '') ? date_create($reqVehicle->arrives_tm) : '';
                        @endphp
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-clock"></i></span>
                        </div>
                        <input type="text" class="form-control time_vehicle" name="arrives_time" id="arrives_time" placeholder="HH:MM" value="{{ ($arrives_tm != '') ? date_format($arrives_tm,"H:i") : '' }}">
                    </div>
                    @error('arrives_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="passenger" class="col-md-4 col-form-label text-md-right">{{ __('Passenger') }}</label>
        
                <div class="col-md-2">
                    <select name="passenger" id="passenger" class="form-control select2bs4">
                        <option value="null">Count</option>
                        @for ($i = 1; $i < 11; $i++)
                            <option value = {{$i}} {!! ($reqVehicle->passenger == $i) ? 'selected' : '' !!}>{{$i}}</option>
                        @endfor
                    </select>
                    @error('passenger')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="goods" class="col-md-4 col-form-label text-md-right">{{ __('Goods') }}</label>
        
                <div class="col-md-1">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="goods_yes" name="goods" value="Y" onclick="get_goods_note();" {!! ($reqVehicle->goods == 'Y') ? 'checked' : '' !!}>
                        <label for="goods_yes" class="custom-control-label">Yes</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="goods_no" name="goods" value="N" onclick="get_goods_note();" {!! ($reqVehicle->goods == 'N') ? 'checked' : '' !!}>
                        <label for="goods_no" class="custom-control-label">No</label>
                    </div>
                    @error('goods')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-5" id="yes_goods" style="display:none">
                    <textarea class="form-control" name="goods_note" id="goods_note" placeholder="Goods Note">{{$reqVehicle->goods_note}}</textarea>
                    @error('goods_note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>    
</form> 

<!-- date picker -->
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        }).inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
        
        //Timepicker
        $('.timepicker').timepicker({
            showInputs: false
        })
        $('.time_vehicle').inputmask("datetime", {
            inputFormat: "HH:MM",
            outputFormat: "HH:MM",
            inputEventOnly: true
        });
        
        // $('body').on('shown.bs.modal', '.modal', function() {
            get_goods_note();
        // });
    })
    
    function get_goods_note(){
        var radioValue = $("input[name='goods']:checked").val();
        (radioValue == 'Y') ? $('#yes_goods').show() : $('#yes_goods').hide();
    }
</script>