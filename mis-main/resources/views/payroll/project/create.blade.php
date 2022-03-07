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
                    <div class="row">
                      <div class="col-10 float-left">
                        <div class="col-0.5 float-left d-flex h-100">
                          <label class="justify-content-center align-self-center" style="margin-bottom: unset">Filter:</label>
                        </div>
                          <div class="col-1 float-left">
                            <select class="form-control-sm select2bs4" style="width: 100%;" name="month" id="month">
                                <option value="">Select Month</option>
                                @for ($i = 1; $i <=12; $i++)
                                  @php
                                      $dateObj   = DateTime::createFromFormat('!m', $i)
                                  @endphp
                                  <option value="{{ $i }}">{{ $dateObj->format('F') }}</option>
                                @endfor
                              </select>
                          </div>
                          <div class="col-1 float-left">
                            <select class="form-control-sm select2bs4" style="width: 100%;" name="year" id="year">
                                <option value="">Select Year</option>
                                @for ($i = 2020; $i <= 2200; $i++)
                                  <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                              </select>
                          </div>
                          <div class="col-3 float-left">
                          <select class="form-control-sm select2bs4" style="width: 100%;" name="src_client" id="src_client">
                            <option value="" selected>Select Company</option>
                            @foreach ($companies as $comp)
                              <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-3 float-left">
                          <select class="form-control-sm select2bs4" style="width: 100%;" name="src_job_field" id="src_job_field">
                            <option value="" selected>Select Project</option>
                          </select>
                        </div>
                        <div class="float-left">
                          <button type="button" class="btn btn-secondary" id="btnSelect"> Select</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.row -->
                  </div>
                  <div id="recent">
                  </div>
                  <div class="card-body" id="body" style="display: none">
                    <div class="row">
                      <div class="col-md-6">
                        <h4 id="project"></h4>
                        <h4 id="date"></h4>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="">Cut Off Salary</label>
                              <input type="text" class="form-control date" id="cut-off-salary" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="">Cut Off Allowance</label>
                              <input type="text" class="form-control date" id="cut-off-allowance" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="">Payment Date</label>
                              <input type="text" class="form-control singleDate" id="payment-date" placeholder="">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td style="width: 50%">
                              <h4>Allowance</h4>
                              <hr>
                              <div class="row">
                                  @foreach (App\Models\Payroll\Variables::where('type',1)->get() as $item)
                                  <div class="col-md-3">
                                      <div class="icheck-primary d-inline">
                                          <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                                          <label for="{{ $item->id }}">
                                              {{ $item->name }}
                                          </label>
                                      </div>  
                                  </div>
                                  @endforeach   
                                  @foreach (App\Models\Payroll\Variables::where('type',3)->get() as $item)
                                  <div class="col-md-3">
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
                                  <div class="col-md-3">
                                      <div class="icheck-primary d-inline">
                                          <input type="checkbox" id="{{ $item->id }}" class="variables" value="{{ $item->id }}" name="variables[]">
                                          <label for="{{ $item->id }}">
                                              {{ $item->name }}
                                          </label>
                                      </div>  
                                  </div>
                                  @endforeach   
                                  @foreach (App\Models\Payroll\Variables::where('type',4)->get() as $item)
                                  <div class="col-md-3">
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
                    <hr>
                    <button class="btn btn-primary" type="button" id="btnSubmit">Submit</button>
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
        var company = ''
        var project = ''
        var month = ''
        var year = ''
      //Initialize Select2 Elements
      $('.select2bs4').select2({
          theme: 'bootstrap4'
      })
      $('.date').daterangepicker({
          locale: {
              separator: ' to ',
              format: 'DD-MM-YYYY'
          }
      })
      $('.singleDate').daterangepicker({
          singleDatePicker: true,
          locale: {
              separator: ' to ',
              format: 'DD-MM-YYYY'
          }
      })
    });

    $('#btnSelect').click(function(){
      company = $('#src_client :selected').text();
      project = $('#src_job_field :selected').text();
      month = $('#month :selected').text();
      year = $('#year :selected').text();

      if (company === 'Select Company' || project === 'Select Project' || month === 'Select Month' || year === 'Select Year') {
        Swal.fire({
          type: 'error',
          title: 'Oops...',
          text: 'Please Select Month, Year, Company & Project !'
        });
      } else {
        $.ajax({
          url: '{{ route("payroll.project.recentUse") }}',
          type: "POST",
          data: {
              '_method': 'POST',
              '_token': $('meta[name="csrf-token"]').attr('content'),
              'project': $('#src_job_field :selected').val(),
              'month': $('#month :selected').val(),
                  'year': $('#year :selected').val()

          },
          success: function (response) {

            if (response === 'created') {
              Swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'Already Created'
              });
            } else {
              $('#recent').html(response)

              $("#body").css("display", "block");
              $('#project').html(company + ' | ' + project)
              $('#date').html(month + ' ' + year)
 
              $("#src_client").prop("disabled", true)
              $("#src_job_field").prop("disabled", true)
              $("#month").prop("disabled", true)
              $("#year").prop("disabled", true) 
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
      }
    });



    $('#src_client').change( () => {
        Swal.showLoading();
        var company_id = $('#src_client').val();
        $.ajax({
            url : '{{ route("er.project.group.getData") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
              company_id
            },
            success: (data) => {
                var str_data = '<option value="">Select Project</option>';
                str_data += data.map((project) => {
                    return '<option value="' + project.id + '">' + project.name + '</option>';
                });
                $('#src_job_field').html(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });

    $('#btnSubmit').click(function() {
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
             var variables = $("input[name='variables[]']:checkbox:checked").map(function(){return $(this).val();}).get()
            $.ajax({
                url: '{{ route("payroll.project.store") }}',
                type: "POST",
                data: {
                    '_method': 'POST',
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'variables': variables,
                    'project': $('#src_job_field :selected').val(),
                    'month': $('#month :selected').val(),
                    'cut_off_salary': $('#cut-off-salary').val(),
                    'cut_off_allowance': $('#cut-off-allowance').val(),
                    'payment_date': $('#payment-date').val(),
                    'year': $('#year :selected').val()
                },
                success: function (response) {

                    if (response.res == 'success') {
                        Swal.fire({
                            type: 'success',
                            title: 'Success!',
                            text: 'Data has been Saved !'
                        }); 
                        var url = "{{ url('/payroll/project') }}"+'/'+response.id 
                        window.location.replace(url)                 
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
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
        }
     });
    })
</script>    
@endpush

@endsection
