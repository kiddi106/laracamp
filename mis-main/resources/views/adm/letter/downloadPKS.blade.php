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
                            <div class="card-header"><h4>Download File<h4></div>
                                <div class="card-body">
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
                                                    </div><br>
                                                    
                                                    <button type="submit" class="btn btn-primary">Download File</button>
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
