@extends('layouts.app')

@section('content-header')
   <!-- Content Header (Page header) -->
   <div class="content-header">
        {{-- <div class="container"> --}}
        <div class="row mb-2">
            <div class="col-sm-6">
            	<h1 class="m-0 text-dark">Administration</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Administration</a></li>
					<li class="breadcrumb-item active">Letter</li>
				</ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
    </div>
      <!-- /.content-header -->

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                            <div class="card-header"><h4>Upload File<h4></div>
                                <div class="card-body">
                                        <form method="POST" action="{{ route('adm.store_upload_in') }}" enctype="multipart/form-data">
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
                                                    <div class="form-group row">
                                                        <label for="client_ltr_nmbr" class="col-md-4 col-form-label text-md-right">{{ __('Client Letter Number') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <label class="col-form-label" style="font-weight:normal">{{ $incoming_mail->client_letter_number }}</label>
                                                            @error('destination')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row">
                                                        <label for="maker" class="col-md-4 col-form-label text-md-right">{{ __('Maker') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="id" id="id" value="{{ $incoming_mail->id }}">
                                                            <label class=" col-form-label" style="font-weight:normal">{{ Auth::user()->name }}</label>
                                                            @error('maker')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="attention" class="col-md-4 col-form-label text-md-right">{{ __('Attention to') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <label class="col-form-label" style="font-weight:normal">{{ $incoming_mail->attention_to }}</label>
                                                            @error('destination')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>
                                                        <div class="col-md-6">
                                                         <label class="col-form-label" style="font-weight:normal">{{ $incoming_mail->company }}</label>
                                                            @error('destination')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="subject" class="col-md-4 col-form-label text-md-right">{{ __('Subject') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <label class="col-form-label" style="font-weight:normal">{{ $incoming_mail->subject }}</label>
                                                            @error('destination')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="note" class="col-md-4 col-form-label text-md-right">{{ __('Note') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <label class="col-form-label" style="font-weight:normal">{{ $incoming_mail->note }}</label>
                                                            @error('destination')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="maker" class="col-md-4 col-form-label text-md-right">{{ __('File Surat') }}</label>
                                                
                                                        <div class="col-md-6">
                                                            <input type="file" name="file" id="file">
                                                            @error('maker')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Upload File</button>
                                                </div>
                                            </div>    
                                        </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
