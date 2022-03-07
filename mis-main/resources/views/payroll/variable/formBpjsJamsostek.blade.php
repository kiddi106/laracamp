<form method="POST" action="{{ route('payroll.variables.storeGroup') }}">
    @csrf
<br>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-left">{{ __('Name') }}</label>
        
                <div class="col-md-10">
                    <input id="tkName" type="text" class="form-control" name="tkName" value="{{ $model[0]->name }}" readonly autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-left">{{ __('Max Amount') }}</label>
        
                <div class="col-md-10">
                    <input id="tkMax" type="text" class="form-control" name="tkMax" value="{{ $model[0]->max }}" required autofocus>
                </div>
            </div>
        </div>
    </div>    
</form> 
<script>
</script>