<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<form method="POST" action="{{ route('ga.vehicle.store.schedule') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="req_name" class="col-md-4 col-form-label text-md-right">{{ __('Req Name') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="req_vehicle" id="req_vehicle" value="{{ base64_encode($reqVehicle->req_vehicle_id) }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ $empl->name }}</label>
                    @error('req_name')
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
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="purpose" class="col-md-4 col-form-label text-md-right">{{ __('Purpose') }}</label>
        
                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight:normal">{{ $reqVehicle->purpose }}</label>
                    @error('purpose')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="destination" class="col-md-4 col-form-label text-md-right">{{ __('Destination') }}</label>
        
                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight:normal">{{ $reqVehicle->destination }}</label>
                    @error('destination')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="schedule" class="col-md-4 col-form-label text-md-right">{{ __('Departure Time') }}</label>
        
                <div class="col-md-6">
                    @php
                        $departure_tm = date_create($reqVehicle->departure_tm);
                        $departure_tm = ($reqVehicle->departure_tm != '') ? date_format($departure_tm,"H:i") : '-';
                    @endphp     
                    <label class="col-form-label" style="font-weight:normal">{!! $departure_tm !!}</label>
                    @error('schedule')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="schedule" class="col-md-4 col-form-label text-md-right">{{ __('Pick up Time') }}</label>
        
                <div class="col-md-6">
                    @php
                        $arrives_tm = date_create($reqVehicle->arrives_tm);
                        $arrives_tm = ($reqVehicle->arrives_tm) ? date_format($arrives_tm,"H:i") : '-';
                    @endphp     
                    <label class="col-form-label" style="font-weight:normal">{!! $arrives_tm !!}</label>
                    @error('schedule')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="passenger" class="col-md-4 col-form-label text-md-right">{{ __('Passenger') }}</label>
        
                <div class="col-md-2">
                    <label class="col-form-label" style="font-weight:normal">{!! $reqVehicle->passenger !!}</label>
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
                    <label class="col-form-label" style="font-weight:normal">{!! ($reqVehicle->goods == 'Y') ? 'Yes' : 'No' !!}</label>
                    @error('goods')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-5" id="yes_goods">
                    <label class="col-form-label" style="font-weight:normal">{!! ($reqVehicle->goods == 'Y') ? $reqVehicle->goods_note : '' !!}</label>
                    @error('goods_note')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="vehicle" class="col-md-4 col-form-label text-md-right">{{ __('Vehicle') }}</label>
        
                <div class="col-md-4">
                    <select class="form-control select2bs4 @error('vehicle') is-invalid @enderror" name="vehicle" id="set_vehicle" required onchange="get_driver()">
                        <option value="">Select Vehicle</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ base64_encode($vehicle->vehicle_id) }}">{{ $vehicle->vehicle_license }}</option>
                        @endforeach
                    </select>
        
                    @error('vehicle')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="driver" class="col-md-4 col-form-label text-md-right">{{ __('Driver') }}</label>
        
                <div class="col-md-4">
                    <select class="form-control select2bs4 @error('driver') is-invalid @enderror" name="driver" id="set_driver" required>
                        <option value="">Select driver</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->uuid }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
        
                    @error('driver')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="notes" class="col-md-4 col-form-label text-md-right">{{ __('Notes') }}</label>
        
                <div class="col-md-4">
                    <textarea class="form-control" name="notes" id="notes" placeholder="Notes"></textarea>
                    @error('notes')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>    
</form> 
<!-- Select2 -->
<script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>