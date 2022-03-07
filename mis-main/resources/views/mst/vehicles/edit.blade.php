
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

<form method="POST" action="{{ route('mst.vehicle.update') }}">
    @csrf
<br>
    <div class="row">
        
        <input id="id" type="hidden" class="form-control" name="vehicle_id" value="{{ base64_encode($vehicle->vehicle_id) }}">
        
        <div class="col-md-12">
            <div class="form-group row">
                <label for="vehicle_license" class="col-md-3 col-form-label text-md-right">{{ __('License Plate') }}</label>
        
                <div class="col-md-8">
                    <input id="vehicle_license" type="text" class="form-control @error('vehicle_license') is-invalid @enderror" name="vehicle_license" value="{{ $vehicle->vehicle_license }}" required autocomplete="vehicle_license" autofocus>
        
                    @error('vehicle_license')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="vehicle_type" class="col-md-3 col-form-label text-md-right">{{ __('Vehicle Type') }}</label>
        
                <div class="col-md-8">
                    <input id="vehicle_type" type="text" class="form-control @error('vehicle_type') is-invalid @enderror" name="vehicle_type" value="{{ $vehicle->vehicle_type }}" required autocomplete="vehicle_type" autofocus>
        
                    @error('vehicle_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="vehicle_color" class="col-md-3 col-form-label text-md-right">{{ __('Vehicle Color') }}</label>
        
                <div class="col-md-6">
                    <input id="vehicle_color" type="text" class="form-control @error('vehicle_color') is-invalid @enderror" name="vehicle_color" value="{{ $vehicle->vehicle_color }}" required autocomplete="vehicle_color" autofocus>
        
                    @error('vehicle_color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="max_passenger" class="col-md-3 col-form-label text-md-right">{{ __('Max Passenger') }}</label>
        
                <div class="col-md-6">
                    <input id="max_passenger" type="number" class="form-control @error('max_passenger') is-invalid @enderror" name="max_passenger" value="{{ $vehicle->max_passenger }}" required autocomplete="max_passenger" autofocus>
        
                    @error('max_passenger')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <label class="col-md-2 col-form-label">{{ __('Person') }}</label>
            </div>

            <div class="form-group row" style="margin-top:10px;">
                <label for="driver" class="col-md-3 col-form-label text-sm-right">{{ __('Driver') }}</label>
        
                <div class="col-md-8">
                    <select class="form-control select2bs4 @error('driver') is-invalid @enderror" name="driver" id="driver" required onchange="view_driver();">
                        <option value="">Select Driver</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ base64_encode($driver->uuid) }}"
                                @if ($vehicle->uuid == $driver->uuid)
                                    selected
                                @endif    
                            >{{ $driver->name }}</option>
                        @endforeach
                    </select>
        
                    @error('driver')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>   
            <div id="new_driver" style="display:none;">
                <div class="form-group row">
                    <label for="driver_age" class="col-md-3 col-form-label text-md-right">{{ __('Age') }}</label>
            
                    <div class="col-md-6">
                        <input id="driver_age" type="text" class="form-control @error('driver_age') is-invalid @enderror" name="driver_age" value="{{ old('driver_age') }}" required autocomplete="driver_age" autofocus disabled>
            
                        @error('driver_age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <label class="col-md-2 col-form-label">{{ __('Year') }}</label>
                </div>
                <div class="form-group row">
                    <label for="driver_phone" class="col-md-3 col-form-label text-md-right">{{ __('Phone Number') }}</label>
            
                    <div class="col-md-8">
                        <input id="driver_phone" type="text" class="form-control @error('driver_phone') is-invalid @enderror" name="driver_phone" value="{{ old('driver_phone') }}" required autocomplete="driver_phone" autofocus disabled>
            
                        @error('driver_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>  
            </div>
        </div>
    </div>    
</form> 


<script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    
    $(function () {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        view_driver();
    })

  function view_driver(){
    var driver = $('#driver').val();
    if(driver === ''){
      $('#new_driver').hide();
    }else{
      $('#new_driver').show();
    }
    
    var id = $("#driver").val();
    // AJAX request 
    $.ajax({
        url: "{!! route('mst.vehicle.get.driver') !!}",
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: id,
        },
        success: function(response){
            var year_birth = response.dob.split("-")[0];
            var age = new Date().getFullYear() - year_birth;
            $('#new_driver').show();
            $('#driver_age').val(age);
            $('#driver_phone').val(response.mobile_no);
            // $("#set_driver").val(response.driver_id).prop('selected', true);
            // $('#set_driver').val(response.driver_id); // Select the option with a value of '1'
        }
    });
  }
</script>    