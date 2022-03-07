<form method="POST" action="{{ route('mst.employee.update') }}">
    @csrf
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="current_password" class="col-md-3 col-form-label text-md-right">{{ __('Current Password') }}</label>
                    <div class="col-md-8">
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current_password" value="{{ old('current_password') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>
                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" value="{{ old('password') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password_confirmation" class="col-md-3 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                    <div class="col-md-8">
                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>
                    </div>
                </div>
            </div>
        </div>  
    </div>  
</form> 