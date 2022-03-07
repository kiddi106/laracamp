@extends('layouts.app')
@push('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<!-- date picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project Group</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Project</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content-header -->
  
      <!-- Main content -->
      <div class="content">
        {{-- <div class="container"> --}}
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                      <form action="{{ route('er.project.group.update',base64_encode($model->id)) }}" method="post">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                          <label for="name">Company</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{ $model->company->name }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="name">Project Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $model->name }}">
                        </div>
                        <hr>
                            <div class="col-md-12 row">
                                @foreach (App\Models\Er\Project::where('company_id',$model->company_id)->get() as $item)

                                   @php
                                       $a = array_search($item->code,$data);
                                       if ($a != null || $a === 0)
                                       {
                                           $check = 'checked';
                                       } else {
                                           $check = '';
                                       }
                                   @endphp
                                <div class="col-md-4 row">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="{{ $item->code }}" {{ $check }} name="project[]" value="{{ $item->code }}">
                                            <label for="{{ $item->code }}">
                                                {{ $item->name }}
                                            </label>
                                          </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                          <hr>
                          <button class="btn btn-primary" type="submit">Update</button>
                      </form>
                    </div>
                </div>
            </div>
          </div>
          <!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content -->
@include('layouts._modal')

@push('js')
    <!-- date picker -->
    <script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endpush
@push('scripts')
<script>
    $('.select2').select2({
      theme: 'bootstrap4'
    })

    $('#company').change(function() {
      Swal.showLoading();
        var company_id = $('#company').val();
        $.ajax({
            url : '{{ route("er.project.attendance.getProject") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
              company_id
            },
            success: (data) => {
                // var str_data = '';
                str_data = data.map((project) => {
                    return '<div class="col-md-4 row"><div class="form-group clearfix"><div class="icheck-primary d-inline" id="project"><input type="checkbox" name="project[]" value="' + project.code + '" id="' + project.code + '"><label for="' + project.code + '"> ' + project.name + '</label></div></div></div>';
                });
                $('#project').html(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    })
</script>    
@endpush

@endsection
