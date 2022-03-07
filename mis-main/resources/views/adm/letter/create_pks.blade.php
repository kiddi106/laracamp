<form method="POST" action="{{ route('adm.store_pks') }}">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row" style="margin-top:10px; margin-bottom:unset;">
                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
        
                <div class="col-md-6">
                    <input type="hidden" name="empl_id" id="empl_id" value="{{ base64_encode(Auth::user()->uuid) }}">
                    <input type="hidden" name="date" id="date" value="{{ date('d-m-Y') }}">
                    <label class=" col-form-label" style="font-weight:normal">{{ gmdate('D, d M Y', time()) }}</label>
                    @error('empl_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><br>
            <div class="form-group row" style="margin-bottom:unset;">
                <label for="letter_number" class="col-md-4 col-form-label text-md-right">{{ __('Letter Number') }}</label>
        
                <div class="col-md-6">
                    <label class=" col-form-label" style="font-weight:normal">{{ $nomor_surat_pks }}</label>
                    @error('letter_number')
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
                    @error('maker')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>
        
                <div class="col-md-4">
                    <select class="form-control select2" id="type" name="type">
                        <option value="new">NEW</option>
                        <option value="add">ADDENDUM</option>
                    </select>
                </div>
                <div class="col-md-2" id="add-num" >
                    <select class="form-control select2" name="add_num">
                        @for ($i = 0; $i <= 50; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            
            <div class="form-group row">
                <label for="purpose" class="col-md-4 col-form-label text-md-right">{{ __('Purpose') }}</label>
        
                <div class="col-md-6">
                    <textarea type="text" class="form-control" name="purpose" id="purpose"></textarea>
                    @error('purpose')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div><br>
            <div class="form-group row">
                <label for="subject" class="col-md-4 col-form-label text-md-right">{{ __('Subject') }}</label>
        
                <div class="col-md-6">
                    <textarea class="form-control" name="subject" id="subject"></textarea>
                    @error('subject')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="from_pks" class="col-md-4 col-form-label text-md-right">{{ __('From') }}</label>
        
                <div class="col-md-6">
                    <textarea class="form-control" name="from_pks" id="from_pks"></textarea>
                    @error('from_pks')
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
        $('#PKS_table').DataTable().ajax.reload();
    });
    $('#add-num').hide()
    $("#type").change(function(){
        var type = $('#type').val()

        if (type === "add") {
            $('#add-num').show()
        } 
        else
        {
            $('#add-num').hide()
        }
    })
</script>

