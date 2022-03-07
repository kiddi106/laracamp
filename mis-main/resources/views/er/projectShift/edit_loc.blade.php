<form method="POST" action="{{ route('er.project.shift.update_loc') }}">
    @csrf
    <div class="form-group row">
        <label for="department" class="col-md-4 col-form-label text-md-right">{{ __('Project Name') }}</label>
        <div class="col-md-6">

            <input id="department" type="text" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ $location->department_code }}" required autocomplete="department" readonly autofocus>
            <input type="hidden" name="id" value="{{ base64_encode($location->id) }}">
            
            @error('department')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>    
    </div>

    <div class="form-group row">
        <label for="loc_name" class="col-md-4 col-form-label text-md-right">Location Name</label>

        <div class="col-md-6">
            <input id="loc_name" type="text" class="form-control @error('loc_name') is-invalid @enderror" name="loc_name" value="{{ $location->name }}" required autofocus>

            @error('loc_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>

        <div class="col-md-6">
            <input id="latitude" type="text" class="form-control time @error('latitude') is-invalid @enderror" name="latitude" value="{{ $location->latitude }}" required autofocus>

            @error('latitude')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>

        <div class="col-md-6">
            <input id="longitude" type="text" class="form-control time @error('longitude') is-invalid @enderror" name="longitude" value="{{ $location->longitude }}" required autofocus>

            @error('longitude')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="default_loc" class="col-md-4 col-form-label text-md-right">Default Location </label> <br>

        <div class="col-md-6">
            <label for="default_loc">

                <input type="radio"  id="default_yes" name="default_loc" value="YES" {{ ($location->default=="YES")? "checked" : "" }}> YES </input>
                <br>
                <input type="radio" id="default_no" name="default_loc" value="NO" {{ ($location->default=="NO")? "checked" : "" }}> NO </input>

            </label>
            </div>
    </div>

</form> 
<!-- InputMask -->
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<!-- Select2 -->
{{-- <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script> --}}
<script>
      $('.select2bs42').select2({
        theme: 'bootstrap4'
      })

    </script>
