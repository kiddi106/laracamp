@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="/admin/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker.css">
    <!-- date picker -->
    <link rel="stylesheet" href="/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css">
@endpush

<div class="row">
    <!-- /.col -->
    <div class="col-md-4">
      <div class="form-group">
        <label>Vehicle</label>
        <select class="form-control select2bs4" style="width: 100%;">
          <option selected="selected">B 1 AB | Avanza</option>
          <option>B 2 CD | Luxio</option>
          <option>B 3 EF | Mercedes</option>
        </select>
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
        <div class="col-md-3">
            &nbsp;
      </div>
      <!-- /.col -->
        <div class="col-md-4">
          <div class="form-group">
              <label>&nbsp;</label>
              <div align="right">
                    <a href="{{ route('ga.vehicle.create') }}" name="create_request" id="create_request" class="btn btn-success btn-sm modal-show" title="Create New Request Vehicle"><i class="fa fa-plus"></i> New Request</a>
              </div>
          </div>
      </div>
      <!-- /.col -->
  </div>
  <!-- /.row -->
  <div class="table-responsive">
    <table id="datatable" class="table table-bordered table-striped table-sm" style="width: 100% !important">
        <thead>
            <tr>
                <th>No</th>
                <th>Depatemen</th>
                <th>Req Name</th>
                <th>Ext</th>
                <th>Depature Time</th>
                <th>Arrives Time</th>
                <th>Purpose</th>
                <th>Destination</th>
                <th>Driver</th>
                <th>Note</th>
            </tr>
        </thead>
    </table>
</div>
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
    $(document).ready(function(){
        $('#datatable').DataTable({
            scrollX : true,
            scrollCollapse : true,
            searching: false,
            orderCellsTop: true,
            fixedHeader: true,
            processing: true,
            serverSide: true,
            cache: false,
            order: [[6, 'desc']],
            ajax: "{!! route('ga.vehicle.list') !!}",
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: 'departement', name: 'departement'},
                {data: 'created_by', name: 'req_name'},
                {data: 'ext', name: 'ext'},
                {data: 'departure_tm', name: 'departure_time'},
                {data: 'arrives_tm', name: 'arrives_time'},
                {data: 'purpose', name: 'purpose'},
                {data: 'destination', name: 'destination'},
                {data: 'driver', name: 'driver'},
                {data: 'notes', name: 'notes'}
            ]
        });
        
        $('#btnSearch').click(function(){
            $('#datatable').DataTable().draw(true);
        })
    });
</script>
@endpush
