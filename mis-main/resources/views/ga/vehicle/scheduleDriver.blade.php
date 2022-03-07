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
    <div class="col-md-2">
      <div class="form-group">
        <label>Vehicle</label>
        <select class="form-control select2bs4" style="width: 100%;" name="src_vehicle" id="src_vehicle">
          <option value="null">Select Vehicle</option>
          @foreach ($vehicles as $vehicle)
            <option value="{{ base64_encode($vehicle->vehicle_id) }}">{{ $vehicle->vehicle_license }}</option>
          @endforeach
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
  </div>
  <!-- /.row -->
  <div class="table-responsive">
    <table id="datatable" class="table table-bordered table-striped table-sm" style="width: 100% !important">
        <thead>
            <tr>
                <th>No</th>
                <th>Departement</th>
                <th>Req Name</th>
                <th>Ext</th>
                <th>Depature Time</th>
                <th>Pick up Time</th>
                <th>Purpose</th>
                <th>Destination</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Status</th>
                <th>Note</th>
            </tr>
        </thead>
    </table>
</div>
@push('scripts')
	<script>
		$(document).ready(function(){
      
      $('.select2bs4').select2({
          theme: 'bootstrap4'
      })

      $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd/mm/yyyy'
      }).inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

      $('.datepicker').datepicker().datepicker("setDate", new Date())
      
      //Timepicker
      $('.timepicker').timepicker({
          showInputs: false
      })
      
			$('#datatable').DataTable({
				scrollX : true,
				scrollCollapse : true,
				searching: true,
				orderCellsTop: true,
				fixedHeader: true,
				processing: true,
				serverSide: true,
				cache: false,
        ajax: {
          url: "{!! route('ga.vehicle.listDriver') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val(),
                vehicle: $("#src_vehicle").val(),
            })
          }
        },
				columns: [
          {data: 'DT_RowIndex', name: 'req_vehicle_id'},
					{data: 'departement', name: 'departement'},
					{data: 'req_name', name: 'req_name'},
					{data: 'ext', name: 'ext'},
					{data: 'departure_tm', name: 'departure_time'},
					{data: 'arrives_tm', name: 'pickup_time'},
					{data: 'purpose', name: 'purpose'},
					{data: 'destination', name: 'destination'},
					{data: 'name', name: 'driver_name'},
					{data: 'vehicle', name: 'vehicle'},
					{data: 'status', name: 'status'},
					{data: 'notes', name: 'notes'}
        ],
        columnDefs: [{
            "targets" : "no-sort",
            "orderable" : false,
        }]
			});
			
			$('#btnSearch').click(function(){
				$('#datatable').DataTable().draw(true);
			})
		});
    </script>
@endpush
