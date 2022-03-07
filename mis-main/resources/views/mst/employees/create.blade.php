<form method="POST" action="{{ route('config.menu.store') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
        
                <div class="col-md-8">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-4 col-form-label text-md-right">Level Menu</label>
                <div class="col-md-8">
                    <select class="form-control select2" name="level">
                            {{-- <option class="form-control" value="">Select Level</option> --}}
                            @for ($i = 1; $i <= 5; $i++)
                                <option class="form-control" value="{{ $i }}">{{ $i }}</option>                            
                            @endfor
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="parent" class="col-md-4 col-form-label text-md-right">Parent Menu</label>
                <div class="col-md-8">
                    <select class="form-control select2" name="parent">
                            <option class="form-control" value="">Select Menu</option>
                            @foreach ($menu as $item)
                            <option class="form-control" value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="url" class="col-md-4 col-form-label text-md-right">Url</label>
        
                <div class="col-md-8">
                    <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="" required autofocus>
        
                    @error('url')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>    
        
            <div class="form-group row">
                <label for="icon" class="col-md-4 col-form-label text-md-right">Icon</label>
        
                <div class="col-md-8">
                    <input id="icon" type="text" class="form-control @error('icon') is-invalid @enderror" name="icon" value="{{ old('icon') }}" required autofocus>
        
                    @error('icon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div> 
            
            <div class="form-group row">
                <label for="order_no" class="col-md-4 col-form-label text-md-right">Order No</label>
        
                <div class="col-md-8">
                    <input id="order_no" type="text" class="form-control @error('order_no') is-invalid @enderror" name="order_no" value="{{ old('order_no') }}" required autofocus>
        
                    @error('order_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div> 

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Roles</label>
                <div class="row">
                 @foreach ($roles as $role)
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="role[]" id="role{!! $role->id !!}" value="{!! $role->id !!}">
                                <label for="role{!! $role->id !!}" class="custom-control-label">{!! $role->display_name !!}</label>
                            </div>     
                        </div>
                    @endforeach
                </div>
            </div>            
        </div>
    </div>    
</form> 