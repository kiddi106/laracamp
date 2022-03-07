<form method="POST" action="{{ route('adm.store_ops') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row" style="margin-top:10px; margin-bottom:unset;">
                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="empl_id" id="empl_id" value="{{ Auth::user()->uuid }}">
                    <input type="hidden" name="date" id="date" value="{{ date('d-m-Y') }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ gmdate('D, d M Y', time()) }}</label>
                    @error('req_time')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><br>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="letter_number" class="col-md-4 col-form-label text-md-right">{{ __('Letter Number') }}</label>
        
                <div class="col-md-6">
                    <label class=" col-form-label" style="font-weight:normal">{{ $nomor_surat_ops }}</label>
                    @error('ext')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div></br>
            <div class="form-group row">
                <label for="maker" class="col-md-4 col-form-label text-md-right">{{ __('Maker') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="maker" id="maker" value="{{ Auth::user()->uuid }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ Auth::user()->name }}</label>
                    @error('req_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="subject" class="col-md-4 col-form-label text-md-right">{{ __('Subject') }}</label>
        
                <div class="col-md-6">
                    <textarea class="form-control" name="subject" id="subject"></textarea>
                    @error('destination')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><br>
            <div class="form-group row">
                <label for="from_ops" class="col-md-4 col-form-label text-md-right">{{ __('From') }}</label>
        
                <div class="col-md-6">
                    <input type="text" class="form-control" name="from_ops" id="from_ops">
                    @error('destination')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><br>
            <div class="form-group row">
                <label for="to_ops" class="col-md-4 col-form-label text-md-right">{{ __('To') }}</label>
        
                <div class="col-md-6">
                    <input type="text" class="form-control" name="to_ops" id="to_ops">
                    @error('destination')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>    
</form> 

<script>
    $('#modal-btn-save').click(function(){
    $('#memo_table').DataTable().ajax.reload();
});
</script>

