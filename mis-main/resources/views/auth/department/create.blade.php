<br>
<form method="POST" action="{{ route('config.department.store') }}">
    @csrf

    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">Code</label>

        <div class="col-md-6">
            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus>

            @error('code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="level" class="col-md-4 col-form-label text-md-right">Parent Department</label>
        <div class="col-md-6">
            <select class="form-control select2" name="parent_code">
                <option class="form-control" value="">Select Department</option>
                @foreach ($departments as $department)
                <option class="form-control" value="{{ $department->code }}">{{ $department->name }}</option>
            @endforeach
        </select>
        </div>
    </div>

</form> 