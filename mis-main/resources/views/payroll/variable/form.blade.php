<form method="POST" action="{{ $model->exists ? route('payroll.variables.update', base64_encode($model->id)) : route('payroll.variables.store') }}">
    @csrf
    @if ($model->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif
<br>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="name" class="col-md-2 col-form-label text-md-left">{{ __('Name') }}</label>
        
                <div class="col-md-10">
                    <input id="name" type="text" class="form-control" name="name" value="{{ $model->name }}" required autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-2 col-form-label text-md-left">Counter Type</label>
                <div class="col-md-10">
                    <select class="form-control select2" name="counter">
                        <option value="">Select Type</option>                            
                        <option {{ $model->counter == 1 ? 'selected' : '' }} value="1">Per Day</option>                            
                        <option {{ $model->counter == 2 ? 'selected' : '' }} value="2">Per Month</option>                            
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-2 col-form-label text-md-left">Type</label>
                <div class="col-md-10">
                    <select class="form-control select2" name="type">
                        <option value="">Select Type</option>                            
                        <option {{ $model->type == 1 ? 'selected' : '' }} value="1">Allowance</option>                            
                        <option {{ $model->type == 2 ? 'selected' : '' }} value="2">Deduction</option> 
                        <option {{ $model->type == 3 ? 'selected' : '' }} value="3">Allowance Irregular</option>                            
                        <option {{ $model->type == 4 ? 'selected' : '' }} value="4">Deduction Irregular</option>                            
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-2 col-form-label text-md-left">Group</label>
                <div class="col-md-10">
                    <select class="form-control select2" name="group">
                        <option {{ $model->group_id == 0 ? 'selected' : '' }} value="0">Select Group</option>                            
                        <option {{ $model->group_id == 0 ? 'selected' : '' }} value="0">None</option>                         
                        <option {{ $model->group_id == 2 ? 'selected' : '' }} value="2">BPJS TK</option>                    
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-2 col-form-label text-md-left">Model</label>
                <div class="col-md-10">
                    <select class="form-control select2" id="model" name="model">
                        <option value="">Select Model</option>
                        <option {{ $model->model == 1 ? 'selected' : '' }} value="1">Nominal</option>
                        <option {{ $model->model == 2 ? 'selected' : '' }} value="2">Percentage (%)</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="level" class="col-md-2 col-form-label text-md-left">Tax Counter</label>
                <div class="col-md-10">
                    <select class="form-control select2" id="tax_counter" name="tax_counter">
                        <option {{ $model->tax_counter == 1 ? 'selected' : '' }} value="1">Yes</option> 
                        <option {{ $model->tax_counter == 0 ? 'selected' : '' }} value="0">No</option>
                    </select>
                </div>
            </div>
            <div id="persen" style="display: none">
                <div class="form-group row" >
                    <label for="percentage" class="col-md-2 col-form-label text-md-left">{{ __('(%)') }}</label>
            
                    <div class="col-md-10">
                        <input id="percentage" type="number" step="0.01" class="form-control" name="percentage"  value="{{ $model->percentage }}" autofocus>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</form> 
<script>
    $(document).ready(function() {
        if ($("#model").val() == 2) {
            $("#persen").css("display", "block");
        } else {
            $("#persen").css("display", "none");
        }
    })

    $('#model').change(function() {
        if ($("#model").val() == 2) {
            $("#persen").css("display", "block");
        } else {
            $("#persen").css("display", "none");
        }
    })
</script>