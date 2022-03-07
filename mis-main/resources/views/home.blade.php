@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"> Home</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Home</a></li>
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
                    <h4>{{ Auth::user()->name }} - <small>{{ Auth::user()->empl_id }}</small></h4>
                </div>
                <div class="col-md-6">
                    @if ($check_in)
                        <a href="#" class="btn btn-success float-right" id="check-in">Check In</a>
                    @elseif ($check_out)
                    <dl class="dl-horizontal">
                        @permission('create-daily-task')
                            <a href="#" class="btn btn-danger float-right" onclick="event.preventDefault();document.getElementById('attendance-out-form').submit();">Check Out</a>
                        @endpermission
                        <a href="{{ route('attendance.task.form',['id_attendance'=>$attendance_id]) }}" class="btn btn-primary float-right show-loc" title="Add Task" style="margin: 0px 2px 0px 2px"> Daily Update</a>
                        <form id="attendance-out-form" action="{{ route('attendance.update', [$attendance_id]) }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" value="" class="loc" name="loc">
                            @method('PUT')
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            You are logged in!
        </div>
    </div>
    <div class="modal fade" id="modal-location" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header" id="modal-header">
                    <h4 class="modal-title" id="modal-title">Location</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
    
                <div class="modal-body" id="modal-body-loc">
                </div>
    
                <div class="modal-footer" id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-att">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Attendance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
    
                <div class="modal-body">
                    <form id="attendance-in-form" action="{{ route('attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" class="loc" name="loc">
                        <input type="hidden" value="{{ $id_shift }}" class="id_shift" name="id_shift">
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

                            </div>
                        </div>
                </div>
    
                <div class="modal-footer justify-content-between" id="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Check In</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
<script>
    $(document).ready( function () {
        getLocation()
    });

        function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            alert("Geolocation is not supported by this browser.")
        }
        }
        function showPosition(position) {
            var loc = position.coords.latitude+', '+position.coords.longitude
            $.ajax({
                url: "{{ route('attendance.getLocation') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {loc: loc},
                dataType: 'html',
                success: function (response) 
                {
                    $('.loc').val(response)
                }
            });
        }
        $("#check-in").click(function() {
            $("#modal-att").modal("show")
        })
        $('body').on('click', '.show-loc', function (event) {
            event.preventDefault();

            var me = $(this),
                url = me.attr('href'),
                title = me.attr('title');

            $('#modal-title').text(title);
            $('#modal-btn-save').addClass('hide');

            $.ajax({
                url: url,
                dataType: 'html',
                success: function (response) {
                    $('#modal-body-loc').html(response);
                }
            });

            $('#modal-location').modal('show');
        });
        $('[data-mask]').inputmask()
</script>
@endpush