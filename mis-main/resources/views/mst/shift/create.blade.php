<br>
<form method="POST" action="{{ route('mst.shift.store') }}">
    @csrf

    <div class="form-group row">
        <label for="shift_cd" class="col-md-4 col-form-label text-md-right">Shift Code</label>

        <div class="col-md-6">
            <input id="shift_cd" type="text" class="form-control @error('shift_cd') is-invalid @enderror" name="shift_cd" value="{{ old('shift_cd') }}" required autocomplete="name" autofocus>

            @error('shift_cd')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
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

</form> 
<!-- InputMask -->
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
        $('.time').inputmask("datetime", {
            inputFormat: "HH:MM",
            outputFormat: "HH:MM",
            inputEventOnly: true
        });

    </script>