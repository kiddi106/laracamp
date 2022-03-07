<div class="card card-primary card-outline card-tabs">
    <div class="card-header p-0 pt-1 border-bottom-0">
      <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="custom-tabs-three-contition-tab" data-toggle="pill" href="#custom-tabs-three-contition" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Condition Update</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="custom-tabs-three-task-tab" data-toggle="pill" href="#custom-tabs-three-task" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Task</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content" id="custom-tabs-three-tabContent">
        <div class="tab-pane fade show active" id="custom-tabs-three-contition" role="tabpanel" aria-labelledby="custom-tabs-three-contition-tab">
            <form id="update-condition" method="POST" action="{{ route('attendance.c19') }}">
                @csrf
                <input type="hidden" value="" class="loc" name="loc">
                <input type="hidden" value="{{ $id }}"  name="id_attendance">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Masker</label>
                    
                            <div class="col-md-8">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input check" type="radio" name="masker" id="masker-yes" value="Y">
                                    <label for="masker-yes" class="custom-control-label">Yes</label>
                                </div>     
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input check" type="radio" name="masker" id="masker-no" value="N">
                                    <label for="masker-no" class="custom-control-label">No</label>
                                </div>     
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Hand Sanitizer</label>
                    
                            <div class="col-md-8">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input check" type="radio" name="hand_sanitizer" id="hand_sanitizer-yes" value="Y">
                                    <label for="hand_sanitizer-yes" class="custom-control-label">Yes</label>
                                </div>     
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input check" type="radio" name="hand_sanitizer" id="hand_sanitizer-no" value="N">
                                    <label for="hand_sanitizer-no" class="custom-control-label">No</label>
                                </div>     
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Body Temperature</label>
                    
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="temperature" name="temperature" data-inputmask='"mask": "99,9"' data-mask>
                            </div>
                        </div>
                        <div class="form-group row">
                                <button type="submit" class="btn btn-success" id="update"> Update Condition</button>
                        </div>
                        <table class="table table-bordered table-sm" id="table-c19" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Masker</th>
                                    <th>Hand Sanitizer</th>
                                    <th>Body Temperature</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="custom-tabs-three-task" role="tabpanel" aria-labelledby="custom-tabs-three-task-tab">
            <form method="POST" id="form">
                @csrf
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Task</label>
                    
                            <div class="col-md-8">
                                <textarea id="note" class="form-control" name="note" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">Progress (%)</label>
                    
                            <div class="col-md-8">
                                <input id="progress" type="text" class="form-control" name="progress" placeholder="100" value="" required >
                                <input id="attendance_id" type="hidden" class="form-control" name="attendance_id" placeholder="100" value="{{ $id }}" required >
                                <input type="hidden" class="form-control loc" name="location" value="" required >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right"></label>
                    
                            <div class="col-md-8">
                                <button id="add-task" type="button" class="btn btn-success" name="btn"><i class='fa fa-plus'></i> Add</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-sm" id="table-task" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Task</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>    
            </form>
        </div>
      </div>
    </div>
    <!-- /.card -->
  </div>
  

<!-- InputMask -->
    <script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
    $(document).ready( function () {
                    $('.loc').val('')
        getLocation()
    });
    $('#add-task').click(function(){
        Swal.fire({
        title: 'Are You Sure?',
        text: 'Task Cannot Be Deleted',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel'
        }).then((result) => 
        {
            if (result.value) 
            {
                $.ajax({
                url: "{{ route('attendance.task.store') }}",
                dataType: 'html',
                type: 'put',
                data: $("form").serialize(),
                success: function (response) 
                    {
                        if (response) 
                        {
                            Swal.fire({
                            type: 'success',
                            title: 'Task Added',
                            })   
                        $('#table-task').DataTable().ajax.reload();
                        $('#note').val('')
                        $('#progress').val('')
                        }
                        else
                        {
                            Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something Wrong, Please Allow to Access Location',
                            })  
                        }
                    },
                    error : function (xhr) {
                        Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something Wrong, Please Allow to Access Location',
                        })  
                    }
                })
            }
        })        

    });

$('#table-task').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('attendance.task.show',[$id]) }}",
                type: 'get'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'note', name: 'note' },
                { data: 'progress', name: 'progress' },
            ]
        });

        $('#table-c19').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('attendance.c19Show',[$id]) }}",
                type: 'get'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id' },
                { data: 'masker', name: 'masker' },
                { data: 'hand_sanitizer', name: 'hand_sanitizer' },
                { data: 'temperature', name: 'temperature' },
            ]
        });
        1
	
        $('[data-mask]').inputmask()
</script>