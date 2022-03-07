@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- date picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
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
        #btnReq {
            position: absolute;
            z-index: 100;
            position: fixed;
            padding: 15px 16px;
            top: 200px;
            right: 90px;
            border-radius:18%;
            box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
        }
    </style>
@endpush

<div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label>Date</label>
        <div class="input-group">
            <input type="text" class="form-control datepicker" name="date" id="src_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" placeholder="dd/mm/yyyy">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
        </div>
      </div>
      <!-- /.form-group -->
    </div>
    <!-- /.col -->
    <div class="col-md-1">
          <div class="form-group">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-default form-control" id="btnSearch"><i class="fa fa-search"></i> Search</button>
          </div>
    </div>
    <!-- /.col -->
    <div class="col-md-8">
          <div class="form-group">
                <label>&nbsp;</label>
              <div align="left">
                @permission('create-req-letter')
                <div class="btn-group" role="group">
                  <button id="btnReq" type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnReq">
                    <a href="{{route('adm.create_pks')}}" class="dropdown-item modal-show" title="Create New Request PKS Sales Marketing Letter">PKS Sales Marketing</a>
                    <a href="{{route('adm.create_ops')}}" class="dropdown-item modal-show" title="Create New Request Internal Memo Letter">Internal Memo</a>
                    <a href="{{route('adm.create_mrkt')}}" class="dropdown-item modal-show" title="Create New Request Memo Marketing Letter">Memo Marketing</a>
                    <a href="{{route('adm.create_it')}}" class="dropdown-item modal-show" title="Create New Request Memo IT Letter">Memo IT</a>
                    <a href="{{route('adm.create_hr')}}" class="dropdown-item modal-show" title="Create New Request Memo HR Letter">Memo HR</a>
                    <a href="{{route('adm.create_sales')}}" class="dropdown-item modal-show" title="Create New Request Sales Confirmation Letter">Sales Confirmation</a>
                    <a href="{{route('adm.create_out')}}" class="dropdown-item modal-show" title="Create New Request Outgoing Mail Letter">Outgoing Mail</a>
                    <a href="{{route('adm.create_in')}}" class="dropdown-item modal-show" title="Create New Request Incoming Mail Letter">Incoming Mail</a>
                  </div>
                </div>
                @endpermission
              </div>
          </div>
          <!-- /.form-group -->
    </div>
    <!-- /.col -->  
    <div class="col-12">
    <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-PKS-tab" data-toggle="pill" href="#custom-tabs-four-PKS" role="tab" aria-controls="custom-tabs-four-PKS" aria-selected="true">PKS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-IM-tab" data-toggle="pill" href="#custom-tabs-four-IM" role="tab" aria-controls="custom-tabs-four-IM" aria-selected="false">IM</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-Marketing-tab" data-toggle="pill" href="#custom-tabs-four-Marketing" role="tab" aria-controls="custom-tabs-four-Marketing" aria-selected="false">Marketing</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-IT-tab" data-toggle="pill" href="#custom-tabs-four-IT" role="tab" aria-controls="custom-tabs-four-" aria-selected="false">IT</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-HR-tab" data-toggle="pill" href="#custom-tabs-four-HR" role="tab" aria-controls="custom-tabs-four-" aria-selected="false">HR</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-Sales-tab" data-toggle="pill" href="#custom-tabs-four-Sales" role="tab" aria-controls="custom-tabs-four-Sales" aria-selected="false">Sales</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-Outgoing-tab" data-toggle="pill" href="#custom-tabs-four-Outgoing" role="tab" aria-controls="custom-tabs-four-Outgoing" aria-selected="false">Outgoing</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-Incoming-tab" data-toggle="pill" href="#custom-tabs-four-Incoming" role="tab" aria-controls="custom-tabs-four-Incoming" aria-selected="false">Incoming</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-four-PKS" role="tabpanel" aria-labelledby="custom-tabs-four-PKS-tab">
                      <div class="table-responsive">
                        <table id="PKS_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                          <thead style="width:100%">
                            <tr>
                              <th>No</th>
                              <th>Created Date</th>
                              <th>Letter Number</th>
                              <th>Purpose</th>
                              <th>Subject</th>
                              <th>From</th>
                              <th>Maker</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                      </div>  
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-IM" role="tabpanel" aria-labelledby="custom-tabs-four-IM-tab">
                  <div class="table-responsive">
                    <table id="memo_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Created Date</th>
                          <th>Letter Number</th>
                          <th>Subject</th>
                          <th>From</th>
                          <th>To</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>  
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-Marketing" role="tabpanel" aria-labelledby="custom-tabs-four-Marketing-tab">
                  <div class="table-responsive">
                    <table id="marketing_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Created Date</th>
                          <th>Letter Number</th>
                          <th>Subject</th>
                          <th>From</th>
                          <th>To</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div> 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-IT" role="tabpanel" aria-labelledby="custom-tabs-four-IT-tab">
                  <div class="table-responsive">
                    <table id="it_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Created Date</th>
                          <th>Letter Number</th>
                          <th>Subject</th>
                          <th>From</th>
                          <th>To</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div> 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-HR" role="tabpanel" aria-labelledby="custom-tabs-four-HR-tab">
                  <div class="table-responsive">
                    <table id="hr_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Created Date</th>
                          <th>Letter Number</th>
                          <th>Subject</th>
                          <th>From</th>
                          <th>To</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div> 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-Sales" role="tabpanel" aria-labelledby="custom-tabs-four-Sales-tab">
                    <div class="table-responsive">
                      <table id="sales_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                        <thead style="width:100%">
                          <tr>
                            <th>No</th>
                            <th>Created Date</th>
                            <th>Letter Number</th>
                            <th>Purpose</th>
                            <th>Note</th>
                            <th>Maker</th>
                            <th></th>
                          </tr>
                        </thead>
                      </table>
                    </div> 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-Outgoing" role="tabpanel" aria-labelledby="custom-tabs-four-Outgoing-tab">
                  <div class="table-responsive">
                    <table id="outgoing_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Created Date</th>
                          <th>Letter Number</th>
                          <th>Attention to</th>
                          <th>Company</th>
                          <th>Subject</th>
                          <th>From</th>
                          <th>Note</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div> 
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-Incoming" role="tabpanel" aria-labelledby="custom-tabs-four-Incoming-tab">
                  <div class="table-responsive">
                    <table id="incoming_table" class="table table-bordered table-striped table-sm" class="display" style="width:100% !important">
                      <thead style="width:100%">
                        <tr>
                          <th>No</th>
                          <th>Client Letter Number</th>
                          <th>Attention to</th>
                          <th>Company</th>
                          <th>Subject</th>
                          <th>Note</th>
                          <th>Maker</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div> 
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
    </div>
<!-- </div> -->
<!-- /.row -->

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
    $('#custom-tabs-four-PKS-tab').click(function(){
      $('#PKS_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_PKS') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },

          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()  
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'purpose', name: 'purpose'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
			});
    })
    $('#custom-tabs-four-IM-tab').click(function(){
      $('#memo_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_IM') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'to', name: 'to'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
			});
    })
    $('#custom-tabs-four-Marketing-tab').click(function(){
      $('#marketing_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_mrkt') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'to', name: 'to'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $('#custom-tabs-four-IT-tab').click(function(){
      $('#it_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_it') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'to', name: 'to'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $('#custom-tabs-four-HR-tab').click(function(){
      $('#hr_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_hr') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'to', name: 'to'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $('#custom-tabs-four-Sales-tab').click(function(){
      $('#sales_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_sales') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'purpose', name: 'purpose'},
          {data: 'note', name: 'note'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $('#custom-tabs-four-Outgoing-tab').click(function(){
      $('#outgoing_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_out') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'attention_to', name: 'attention_to'},
          {data: 'company', name: 'company'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'note', name: 'note'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $('#custom-tabs-four-Incoming-tab').click(function(){
      $('#incoming_table').DataTable({
        destroy: true,
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_in') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },

          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()  
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'client_letter_number', name: 'client_letter_number'},
          {data: 'attention_to', name: 'attention_to'},
          {data: 'company', name: 'company'},
          {data: 'subject', name: 'subject'},
          {data: 'note', name: 'note'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
      });
    })
    $(document).ready(function(){
      $('#PKS_table').DataTable({
        pageLength : 3,
        scrollX : true,
        scrollCollapse : true,
        searching: false,
        orderCellsTop: true,
        processing: true,
        serverSide: true,
        cache: false,
        order: [[0, 'desc']],
        ajax: {
          url: "{!! route('adm.listLetter_PKS') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },

          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val()  
            })
          }
        },
        columns: [
          {data: 'DT_RowIndex', name: 'id'},
          {data: 'created_at', name: 'created_at'},
          {data: 'letter_number', name: 'letter_number'},
          {data: 'purpose', name: 'purpose'},
          {data: 'subject', name: 'subject'},
          {data: 'from', name: 'from'},
          {data: 'created_by', name: 'created_by'},
          {data: 'action', name: 'action'},
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
			});

      $('#btnSearch').click(function(){
        $('#PKS_table').DataTable().draw(true);
        $('#memo_table').DataTable().draw(true);
        $('#marketing_table').DataTable().draw(true);
        $('#it_table').DataTable().draw(true);
        $('#hr_table').DataTable().draw(true);
        $('#sales_table').DataTable().draw(true);
        $('#outgoing_table').DataTable().draw(true);
        $('#incoming_table').DataTable().draw(true);
      })
    });
  </script>
  <!-- /.tables Incoming Mail -->

  <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy'
            }).inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
            
            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>
@endpush
