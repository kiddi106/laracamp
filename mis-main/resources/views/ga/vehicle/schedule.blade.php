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
    <div class="col-md-2">
      <div class="form-group">
        <label>Status</label>
        <select class="form-control select2bs4" style="width: 100%;" name="status" id="src_status">
          <option value="null">Select Status</option>
          <option value="{!! base64_encode('R') !!}">{!! 'Requested' !!}</option>
          <option value="{!! base64_encode('S') !!}">{!! 'Scheduled' !!}</option>
          <option value="{!! base64_encode('C') !!}">{!! 'Cancelled' !!}</option>
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
        <div class="col-md-4">
          <div class="form-group">
              <label>&nbsp;</label>
              <div align="right">
                @permission('create-req-vehicle')
                  <a href="{{ route('ga.vehicle.create') }}" name="create_request" id="create_request" class="btn btn-success btn-sm modal-show" title="Create New Request Vehicle"><i class="fa fa-plus"></i> New Request</a>
                @endpermission
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
                <th></th>
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
      //Initialize Select2 Elements
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
      
      get_goods_note();

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
          url: "{!! route('ga.vehicle.list') !!}",
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: function (d) {
            return $.extend( {}, d, {
                date: $("#src_date").val(),
                vehicle: $("#src_vehicle").val(),
                status: $("#src_status").val(),
            })
          }
        },
				columns: [
					{data: 'action', name: 'action'},
					{data: 'DT_RowIndex', name: 'nomor'},
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
    
    function get_goods_note(){
        var radioValue = $("input[name='goods']:checked").val();
        (radioValue == 'Y') ? $('#yes_goods').show() : $('#yes_goods').hide();
    }

    function get_driver(){
        var id = $("#set_vehicle").val();
        // AJAX request 
        $.ajax({
            url: "{!! route('ga.vehicle.get.driver') !!}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
            },
            success: function(response){
                $("#set_driver").val(response.uuid).prop('selected', true);
                $('#set_driver').val(response.uuid); // Select the option with a value of '1'
                $('#set_driver').trigger('change'); // Notify any JS components that the value changed
            }
        });
    }
    </script>
@endpush
