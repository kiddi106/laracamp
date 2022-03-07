<form method="POST" action="{{ route('mst.vehicle.updateDriver') }}">
    @csrf
<br>
    <div class="row">

        <input id="id" type="hidden" class="form-control" name="driver_id" value="{{ base64_encode($driver->driver_id) }}">
        
        <div class="col-md-12">   
            <div class="form-group row">
                <label for="driver_name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
        
                <div class="col-md-8">
                    <input id="driver_name" type="text" class="form-control @error('driver_name') is-invalid @enderror" name="driver_name" value="{{ $driver->driver_name }}" required autocomplete="driver_name" autofocus>
        
                    @error('driver_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="driver_age" class="col-md-3 col-form-label text-md-right">{{ __('Age') }}</label>
        
                <div class="col-md-6">
                    <input id="driver_age" type="text" class="form-control @error('driver_age') is-invalid @enderror" name="driver_age" value="{{ $driver->driver_age }}" required autocomplete="driver_age" autofocus>
        
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
                    <input id="driver_phone" type="text" class="form-control @error('driver_phone') is-invalid @enderror" name="driver_phone" value="{{ $driver->driver_phone }}" required autocomplete="driver_phone" autofocus>
        
                    @error('driver_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>  
        </div>
    </div>    
</form> 