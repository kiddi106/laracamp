<form method="POST" action="{{ route('ga.asset.user.store') }}">
    @csrf
    {{-- <input type="hidden" name="asset_id" value="{{$model}}"> --}}
    <div class="row">
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

            <div class="col-md-8">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="display_name" class="col-md-4 col-form-label text-md-right">Department</label>
            <div class="col-md-8">
                <select name="department_code" id="department_code" class="form-control" required>
                    @php
                    $departments = \App\Models\Mst\DepartmentAsset::all();
                    @endphp
                    <option value=""></option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->code }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- <div class="form-group row mb-0">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary float-right">
                    {{ __('Create') }}
        </button>
    </div>
    </div> --}}
    </div>
</form>