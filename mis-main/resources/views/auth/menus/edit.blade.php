<form method="POST" action="{{ route('config.menu.update') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
        
                <div class="col-md-8">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $menu->name }}" required autocomplete="name" autofocus>
        
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
                            <option class="form-control" value="">Select Level</option>
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $check = ($menu->level == $i) ? 'selected' : '' ;
                                @endphp
                                <option class="form-control" value="{{ $i }}" {{$check}}>{{ $i }}</option>                            
                            @endfor
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="parent" class="col-md-4 col-form-label text-md-right">Parent Menu</label>
                <div class="col-md-8">
                    <select class="form-control select2" name="parent">
                            <option class="form-control" value="">Select Menu</option>
                        @foreach ($menus as $item)
                            @if ($menu->menu_id == $item->id)
                                @php
                                    $check = 'selected';
                                @endphp
                            @else
                                @php
                                    $check = '';
                                @endphp
                            @endif
                            <option class="form-control" value="{{ $item->id }}" {{$check}}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>            

            <div class="form-group row">
                <label for="url" class="col-md-4 col-form-label text-md-right">Url</label>
        
                <div class="col-md-8">
                <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{$menu->url}}" required autofocus>
        
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
                    <input id="icon" type="text" class="form-control @error('icon') is-invalid @enderror" name="icon" value="{{ $menu->icon }}" required autofocus>
        
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
                    <input id="order_no" type="text" class="form-control @error('order_no') is-invalid @enderror" name="order_no" value="{{ $menu->order_no }}" required autofocus>
        
                    @error('order_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div> 
        </div>

        <input id="id" type="hidden" class="form-control" name="id" value="{{ base64_encode($menu->id) }}">

        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Roles</label>
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input all" type="checkbox" id="all" onclick="cek()">
                            <label for="all" class="custom-control-label">Check All</label>
                        </div>     
                    </div>
                    <hr>
                 @foreach ($roles as $role)
                 @php
                        $check = '';
                 @endphp
                 @foreach ($menuR as $item)
                    @php
                        if ($role->id == $item->role_id) {
                            $check = 'checked';
                            // @dd();
                        }
                    @endphp
                    @endforeach
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input check" type="checkbox" name="role[]" id="role{!! $role->id !!}" value="{!! $role->id !!}" {!! $check !!}>
                                <label for="role{!! $role->id !!}" class="custom-control-label">{!! $role->display_name !!}</label>
                            </div>     
                        </div>
                    @endforeach
                </div>

            </div>            
        </div>
    </div>    
</form> 

<script>
    function cek() {
        if ($('#all').is(':checked')) {
            $('.check').prop('checked',true);
        } else {
            $('.check').prop('checked',false);
        }
    }
</script>