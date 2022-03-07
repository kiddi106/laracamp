@extends('layouts.app')
@push('css')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<!-- date picker -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
<style>

</style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Project Payroll</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Payroll</li>
                <li class="breadcrumb-item active">Project</li>
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
                  <div class="card-header">
                        <div class="card-tools">
                            <h4><span class="badge badge-{{ $model->status->class }}">{{ $model->status->name }}</span></h4>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h4 id="project">{{ $model->project->name }}</h4>
                                @php
                                    $dateObj   = DateTime::createFromFormat('!m', $model->month);
                                    $monthName = $dateObj->format('F'); // March
                                @endphp 
                                <h4 id="date">{{ $monthName.' - '.$model->year }}</h4>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('payroll.project.updateDate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ base64_encode($model->id) }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Cut Off Salary</label>
                                                <input type="text" class="form-control date" name="cut_off_salary" id="cut-off-salary" value="{{ date_format(date_create($model->cutoff_salary_start),"d-m-Y").' to '.date_format(date_create($model->cutoff_salary_end),"d-m-Y") }}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Cut Off Allowance</label>
                                                <input type="text" class="form-control date" name="cut_off_allowance" id="cut-off-allowance" value="{{ date_format(date_create($model->cutoff_allowance_start),"d-m-Y").' to '.date_format(date_create($model->cutoff_allowance_end),"d-m-Y") }}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group col-md-10">
                                                <label for="">Payment Date</label>
                                                <input type="text" class="form-control singleDate" name="payment_date"  id="payment-date" value="{{ date_format(date_create($model->payment_date),"d-m-Y") }}" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary float-right"><strong>Update</strong></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                  <div class="card-body" id="body">
                    <button type="button" class="mb-2 btn btn-success" data-toggle="modal" data-target="#modal-variable">Add Variables</button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Allowance</h4>
                            <hr>
                            <div class="row">
                                @foreach ($allowance as $key => $item)
                                <div class="col-md-3">
                                    <label>
                                        {{ $item['name'] }} <a href="{{ route('payroll.project.destroyVariable',['id' => base64_encode($item['id'])]) }}" class="btn-delete-variable" title="Remove Variable"><span class="text-red"><i class="fas fa-times"></i></span></a>
                                    </label>                                        
                                </div>
                                @endforeach                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Deduction</h4>
                            <hr>
                            <div class="row">
                                @foreach ($deduction as $item)
                                <div class="col-md-3">
                                    <label>
                                        {{ $item['name'] }} <a href="{{ route('payroll.project.destroyVariable',['id' => base64_encode($item['id'])]) }}" class="btn-delete-variable" title="Remove Variable"><span class="text-red"><i class="fas fa-times"></i></span></a>
                                    </label>                                        
                                </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col text-center">
                            <a href="{{ route('payroll.project.show',['id' => base64_encode($model->id)]) }}" class="btn bg-blue"><strong>DONE</strong></a>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content -->
<div class="modal fade" id="modal-variable">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ route('payroll.project.storeAddVariable') }}" method="post">
                <div class="modal-body-variable">
                    @csrf
                    <input type="hidden" name="projectPayrollId" value="{{ $model->id }}">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                <td style="width: 50%">
                                    <h4>Allowance</h4>
                                    <hr>
                                    <div class="row">
                                        @foreach (App\Models\Payroll\Variables::where('type',1)->get() as $item)
                                        <div class="col-md-6">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                                                <label for="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>  
                                        </div>
                                        @endforeach                                
                                    </div>
                                </td>
                                <td style="width: 50%">
                                    <h4>Deduction</h4>
                                    <hr>
                                    <div class="row">
                                        @foreach (App\Models\Payroll\Variables::where('type',2)->get() as $item)
                                        <div class="col-md-6">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                                                <label for="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>  
                                        </div>
                                        @endforeach                                
                                    </div>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">update</button>
                </div>
            </form>
        </div>
          <!-- /.modal-content -->
    </div>
        <!-- /.modal-dialog -->
</div>
      <!-- /.modal -->
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
          $('.date').daterangepicker({
          locale: {
              separator: ' to ',
              format: 'DD-MM-YYYY'
          }
      })
      $('.singleDate').daterangepicker({
          singleDatePicker: true,
          locale: {
              format: 'DD-MM-YYYY'
          }
      })

$('body').on('click', '.btn-delete-variable', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Are you sure want to Remove Variable ?',
        text: 'You won\'t be able to revert this!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    '_method': 'DELETE',
                    '_token': csrf_token
                },
                success: function (response) {
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been deleted!'
                    });

                    location.reload()
                },
                error: function (xhr) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    });
});

</script>    
@endpush

@endsection
