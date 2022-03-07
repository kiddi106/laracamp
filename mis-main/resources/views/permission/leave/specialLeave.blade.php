@extends('layouts.app')

{{-- @include('datepicker') --}}
@push('css')
  <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('styles')

    <style>
    .dropdown-menu-date {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 10rem;
        padding: .5rem 0;
        margin: .125rem 0 0;
        /* font-size: 1rem; */
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: .25rem;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.175);
	}

        .calendar.right {
        display: none !important;
        }
    </style>
@endpush
@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Permission</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item ">Permission</a></li>
                    <li class="breadcrumb-item active">Special Leave Permission</a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="form-inline">
                <div class="col-md-6">
                    <h3>Special Leave Permission</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mx-sm-3 mb-2">
                        <label>Date:</label>
                    </div>
                    <form class="form-inline">
                        <div class="form-group mx-sm-3 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right daterange" id="date">
                            </div>
                        </div>
                        <button type="button" id="search" class="btn btn-primary mb-2">Search</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal-lg">
                        <i class="fa fa-plus"></i> New From
                      </button>
                </div>
            </div>
            
            <div class="col-md-12">
                <table class="table table-bordered table-sm" style="width: 100%" id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Request Number</th>
                            <th>Name</th>
                            <th>Request Date</th>
                            <th>Request Type</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">New Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <br>
                <!-- date picker -->
                <form method="POST" action="{{ route('permission.leave.storeSpecial') }}" id="form" enctype="multipart/form-data">
                    @csrf
                
                    <div class="form-group row">
                        <label for="shift_cd" class="col-md-4 col-form-label text-md-right">Permission Type</label>
                
                        <div class="col-md-6">
                            <select class="form-control" name="type" id="type">
                                @foreach ($type as $item)
                                    <option value="{{ $item->type_permission_cd }}">{{ $item->type_permission_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="req_date" class="col-md-4 col-form-label text-md-right">Request Date</label>
                
                        <div class="col-md-3">
                            <input id="req_date" type="text" class="form-control" name="req_date" value="{{ date("d/m/Y") }}" readonly>
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="permission_date" class="col-md-4 col-form-label text-md-right">Permission Date</label>
                
                        <div class="col-md-6">
                
                            <div class="input-group">
                                <div class="date">
                                <input type="text" class="form-control date" id="permission_date" name="permission_date" style="display: none">
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group row" id="upload">
                        <label for="sched_out" class="col-md-4 col-form-label text-md-right">File</label>
                
                        <div class="col-md-6">
                            <input type="file" name="file" id="file" accept="application/pdf">
                        </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="sched_out" class="col-md-4 col-form-label text-md-right">Note</label>
                
                        <div class="col-md-6">
                            <textarea class="form-control" name="note" id="note"></textarea>
                        </div>
                    </div>
                
                </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="create">Create</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

@endsection

@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(document).ready( function () {
        datatables()
    });

    $('#search').click(function(){
        datatables()
    })

    $('.daterange').daterangepicker({
		locale: {
            format: 'DD/MM/YYYY'
        }
	})

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    })



function datatables() {
    $(function () {
        $('#datatable').DataTable({
            destroy: true,
            processing: true,
			serverSide: true,
			order: [[1, "desc" ]],
            ajax: {
                url: "{{ route('permission.leave.dataTableSpecial') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function ( d ) {
                    return $.extend( {}, d, {
                        date: $("#date").val()
                    })
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'permission_id' },
                { data: 'permission_id', name: 'permission_id' },
                { data: 'emp_name', name: 'emp_name' },
                { data: 'request_date', name: 'req_date' },
                { data: 'type_permission', name: 'type_permission' },
                { data: 'status', name: 'status_id' },
                { data: 'note', name: 'note' },
                { data: 'action', name: 'action' },
            ]
        });
    })
}


$('#modal-btn-save').show()

$('#type').change(function () {
    if ($('#type').val() === "S-NOA") 
    {
        $('#upload').show()
    }
    else
    {
        $('#upload').hide()
    }
})

// var date = new Date();
// date.setDate(date.getDate());

var dateDisabled ={!! $date !!};

$(function(){
    $('.date').datepicker(
    {
        format: 'dd/mm/yyyy',
        // multidate:true,
        // startDate: new Date(),
        beforeShowDay: function(date){
            dmy = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            if(dateDisabled.indexOf(dmy) != -1){
                return false;
            }
            else{
                return true;
            }
        }
    });
});
$('#create').click(function () {
    var file_data = $('#file').prop('files')[0];   
    var form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('type', $('#type').val());
    form_data.append('note', $('#note').val());
    form_data.append('permission_date', $('#permission_date').val());
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        url: "{{ route('permission.leave.storeSpecial') }}", // point to server-side PHP script 
        dataType: 'html',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(res){
            $('.date').val("").datepicker("update");
            $("#file").val(null)
            $("#note").val(null)
            $('#datatable').DataTable().ajax.reload();
            Swal.fire({
                type : 'success',
                title : 'Success!',
                text : 'Data has been saved!'
            });
        },
            error: function (xhr) {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        
     });
})

</script>
@endpush