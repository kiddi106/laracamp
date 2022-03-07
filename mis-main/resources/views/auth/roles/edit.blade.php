<form method="POST" action="{{ route('config.role.update') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
        
                <div class="col-md-8">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $role->name }}" required autocomplete="name" autofocus>
        
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        
            <div class="form-group row">
                <label for="display_name" class="col-md-4 col-form-label text-md-right">Display Name</label>
        
                <div class="col-md-8">
                    <input id="display_name" type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name" value="{{ $role->display_name }}" required autofocus>
        
                    @error('display_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="display_name" class="col-md-4 col-form-label text-md-right">Parent Role</label>
        
                <div class="col-md-8">
                    <select name="parent" id="parent" class="form-control select2">
                            <option value="">--Select Role--</option>
                        @foreach ($roles as $value)

                        @php
                            if ($value->id == $role->parent_id) {
                                $check = 'selected';
                            }
                            else {
                                $check = '';
                            }
                        @endphp
                            <option value="{{ $value->id }}"{!! $check !!}>{{ $value->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="display_name" class="col-md-4 col-form-label text-md-right">Department</label>
        
                <div class="col-md-8">
                    <select name="departement" id="departement" class="form-control select2">
                        @foreach ($departement as $item)

                        @php
                            if ($item->code == $role->department_code) {
                                $check = 'selected';
                            }
                            else {
                                $check = '';
                            }
                        @endphp
                            <option value="{{ $item->code  }}"{!! $check !!}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>                
        
            <div class="form-group row">
                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
        
                <div class="col-md-8">
                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $role->description }}" required autofocus>
        
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>            
        </div>
        <input id="id" type="hidden" class="form-control" name="id" value="{{ base64_encode($role->id) }}" required autofocus>

        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Permissions</label>
                <div class="row">
                 @foreach ($permissions as $permission)
                    @php
                        $check = '';                                            
                    @endphp
                    @if ($role->hasPermission($permission->name))
                        @php
                            $check = 'checked';                                            
                        @endphp
                    @endif
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="permission[]" id="permission{!! $permission->id !!}" value="{!! $permission->id !!}" {!! $check !!}>
                                <label for="permission{!! $permission->id !!}" class="custom-control-label">{!! $permission->display_name !!}</label>
                            </div>     
                        </div>
                    @endforeach
                </div>


            </div>            
        </div>
    </div>    
</form> 