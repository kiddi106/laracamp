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
                            @if ($model->status_id == 1)
                               <a href="{{ route('payroll.project.edit',['id' => base64_encode($model->id)]) }}" class="btn btn-secondary"><i class="fas fa-edit"></i> <strong>Edit</strong></a>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h4 id="project">{{ $model->group->name }}</h4>
                                @php
                                    $dateObj   = DateTime::createFromFormat('!m', $model->month);
                                    $monthName = $dateObj->format('F'); // March
                                @endphp 
                                <h4 id="date">{{ $monthName.' - '.$model->year }}</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="">Cut Off Salary</label>
                                        <input type="text" class="form-control" readonly id="cut-off-salary" value="{{ date_format(date_create($model->cutoff_salary_start),"d-m-Y").' to '.date_format(date_create($model->cutoff_salary_end),"d-m-Y") }}" placeholder="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="">Cut Off Allowance</label>
                                        <input type="text" class="form-control" readonly id="cut-off-allowance" value="{{ date_format(date_create($model->cutoff_allowance_start),"d-m-Y").' to '.date_format(date_create($model->cutoff_allowance_end),"d-m-Y") }}" placeholder="">
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label for="">Payment Date</label>
                                        <input type="text" class="form-control" readonly id="payment-date" value="{{ date_format(date_create($model->payment_date),"d-m-Y") }}" placeholder="">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="card-body" id="body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Allowance</h4>
                            <hr>
                            <div class="row">
                                @foreach ($allowance as $key => $item)
                                <div class="col-md-3">
                                    <label>
                                        {{ $item['name'] }}
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
                                        {{ $item['name'] }}
                                    </label>                                        
                                </div>
                                @endforeach                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="float-left">List Employee</h4>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route("payroll.project.downloadPaylistEmployee") }}" method="POST">
                                @csrf
                                <input type="hidden" name="projectPayrolId" value="{{ base64_encode($model->id) }}">
                                <button class="btn btn-secondary float-right" type="submit">Download Payroll List</button>
                            </form>
                        </div>
                    </div>
                    <div class="alert alert-warning alert-dismissible">
                        {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> --}}
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                        Please Complete Employee ID, Bank Account, NPWP & Join Date.
                    </div>
                    <table class="table table-sm table-bordered" id="datatable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Join Date</th>
                                <th>NPWP</th>
                                <th>Bank Account</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>
                    {{-- @if ($countEmp != $countEmpPay) --}}
                    <div>
                        <div>
                            <a href="{{ route('payroll.project.pattern',['id'=>$model->id]) }}" type="button" class="btn btn-info float-right">Download Pattern</a>
                        </div>
                         <h4>Upload Variables</h4>

                        <div id="example"></div>
                        <hr>
                        <button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
                    </div>                        
                    {{-- @endif --}}

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
    $(document).ready( function () {
        $(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                // searching: false,
                orderable:true,
                destroy: true,
                info:false,
                ajax: {
                    url: '{{ route('payroll.project.datatablesEmp') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function ( d ) {
                        return $.extend( {}, d, {
                        project_code:"{{ $model->project_code }}",
                        project_payroll_id:"{{ $model->id }}"
                        })
                    }
                },
                columns: [
                    { data: 'check', name: 'check' },
                    { data: 'DT_RowIndex', name: 'id' },
                    { data: 'empl_id', name: 'empl_id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'join_date', name: 'join_date' },
                    { data: 'npwp', name: 'npwp' },
                    { data: 'bank_account', name: 'bank_account' },
                    { data: 'action', name: 'action' },
                    
                ],
                order: [[0, "desc"]]
            });
        });
        const container = document.getElementById('example');
        const hot = new Handsontable(container, {
        // data: data,
        // colHeaders: {!! $variable !!},
        rowHeaders: true,
        startCols: {!! $countA + $countD + 5 !!},
        startRows: 10,
        height: 500,
        renderAllRows: true,
        colWidths: 200,
        height: 'auto',
        licenseKey: 'non-commercial-and-evaluation', // for non-commercial use only
        nestedHeaders:[
            [
                '','','','','',
                {
                label: 'Allowance',
                colspan: {!! $countA !!}
                },{
                    label: 'Deduction',
                    colspan: {!! $countD !!}
                }
            ],
            [
                '','','','','',
            @php
            foreach ($allowance as $key => $value) {
                if ($value['model'] == 2) {
                    echo "{label: '".$value['name']." (%)'},";

                } else {
                echo "{label: '".$value['name']."'},";
                }
            }
            foreach ($deduction as $key => $value) {
                if ($value['model'] == 2) {
                    echo "{label: '".$value['name']." (%)'},";

                } else {
                echo "{label: '".$value['name']."'},";
                }
            }
            @endphp

            ],
            [
                'Employee ID','PTKP','Metode','Pengali','Basic Salary',
            @php
            foreach ($allowance as $key => $value) {
                if ($value['counter'] == 1) {
                    echo "{label: 'Per Days'},";

                } else {
                    echo "{label: 'Per Months'},";
                }
            }
            foreach ($deduction as $key => $value) {
                if ($value['counter'] == 1) {
                    echo "{label: 'Per Days'},";

                } else {
                    echo "{label: 'Per Months'},";
                }
            }
            @endphp

            ],
        ],
        columns: [{
            type: 'text',
        },
        {
            type: 'dropdown',
            source: {!! $ptkp !!}
        },
        {
            type: 'dropdown',
            source: ['Gross','Net']
        },
        {
            type: 'numeric',
        },
        {
            type: 'numeric',
        },
        @php
            foreach ($allowance as $key => $value) {
                if ($value['model'] == 2) {
                    echo "{type:'numeric',readOnly: true},";
                } else {
                    echo "{type:'numeric'},";
                }
            }
            foreach ($deduction as $key => $value) {
                if ($value['model'] == 2) {
                    echo "{type:'numeric',readOnly: true},";
                } else {
                    echo "{type:'numeric'},";
                }
            }
            @endphp
        ]
    });
    $("#btnSubmit").click(function () {
        Swal.fire({
            title: 'Are you sure want to submit ?',
            // text: '',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                hot.validateColumns([1], (valid) => {
                    if (valid) {
                        var tableData = JSON.stringify(hot.getData());
                        $.ajax({
                            url: '{{ route("payroll.project.storeGrid") }}',
                            type: "POST",
                            data: {
                                '_method': 'POST',
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                                'tableData': tableData,
                                'projectPayrollId':'{!! $model->id !!}'
                            },
                            success: function (response) {

                                if (response.res == 'success') {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: 'Data has been Saved !'
                                    }); 
                                    $('#datatable').DataTable().ajax.reload();
                                    location.reload()                 
                                } else if(response.res == 'falseId') {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Row ' +response.data+ ' Employee ID Notfound',
                                        text: 'Please Check Your Value!'
                                    });
                                } else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Please Check Your Value!'
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Please Check Your Value!'
                        });
                    }
                })
            }
        });
    })
});
    
</script>    
@endpush

@endsection
