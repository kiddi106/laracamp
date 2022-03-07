<br>
<!-- Select2 -->
{{-- <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}"> --}}
<form method="POST" action="{{ route('er.project.shift.store') }}">
    @csrf
    <div class="form-group row">
        <label for="shift_nm" class="col-md-4 col-form-label text-md-right">Company</label>
        <div class="col-md-6">
            <select class="form-control select2bs42" style="width: 100%;" name="company" id="company1">
                <option value="">Select Company</option>
                @foreach ($companies as $comp)
                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                @endforeach
            </select>
        </div>    
    </div>

    <div class="form-group row">
        <label for="shift_nm" class="col-md-4 col-form-label text-md-right">Shift Name</label>

        <div class="col-md-6">
            <input id="shift_nm" type="text" class="form-control @error('shift_nm') is-invalid @enderror" name="shift_nm" value="{{ old('shift_nm') }}" required autofocus>

            @error('shift_nm')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="sched_in" class="col-md-4 col-form-label text-md-right">Schedule In</label>

        <div class="col-md-6">
            <input id="sched_in" type="text" class="form-control time @error('sched_in') is-invalid @enderror" name="sched_in" value="{{ old('sched_in') }}" required autofocus>

            @error('sched_in')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right"></label>
        <div class="form-check " >
            <input class="form-check-input" type="checkbox" value="Y"  name="allow_before">
            <label class="form-check-label">Allow Check In Before Date In</label>
          </div>    
    </div>

    <div class="form-group row">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right">Schedule Out</label>

        <div class="col-md-6">
            <input id="sched_out" type="text" class="form-control time @error('sched_out') is-invalid @enderror" name="sched_out" value="{{ old('sched_out') }}" required autofocus>

            @error('sched_out')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="sched_out" class="col-md-4 col-form-label text-md-right"></label>
        <div class="form-check " >
            <input class="form-check-input" type="checkbox" value="Y"  name="allow_after">
            <label class="form-check-label">Allow Check Out After Date Out</label>
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
    
        $('.time').inputmask("datetime", {
            inputFormat: "HH:MM",
            outputFormat: "HH:MM",
            inputEventOnly: true
        });

    </script>
