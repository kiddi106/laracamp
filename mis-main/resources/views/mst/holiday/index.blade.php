@extends('layouts.app')

@include('plugins.daterangepicker')
@include('plugins.fullcalendar')
@include('plugins.select2')
{{-- @include('plugins.datetimepicker') --}}

@push('styles')
    <style>
        .fc-bootstrap .fc-today.alert {
            border-radius: 0;
            background-color: #009ECE;
        }
    </style>

  <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush
@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Set - Holiday</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Set Holiday</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <input type="button" id="create_form" class="btn btn-success float-right" value="Set Holiday">
                    {{-- <button class="btn btn-success modal-show float-right" onclick="createForm()">New Request</button> --}}
                </div>
            </div>
            <hr>
            <!-- THE CALENDAR -->
            <div class="d-flex justify-content-center">
                <div id="calendar" style="width: 70%"></div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</div>

<div class="modal fade" id="modal1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modal-title1">Create User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modal-body1" class="modal-body">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modal-btn-save1">
                    Save Changes
                </button>
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

    $(function () {
        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');
        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            'themeSystem': 'bootstrap',
            minTime: "08:00",
            maxTime: "22:00",
            eventSources: [{
                url: "{{ route('mst.holiday.search') }}",
                method: 'POST',
                extraParams: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                failure: function() {
                    alert('there was an error while fetching events!');
                },

            }],
            eventClick: function (info, jsEvent, view) {

                $('#modal-btn-save1').show();
                $.ajax({
                    url: "{{ route('mst.holiday.index') }}/"+info.event.id+"/edit",
                    dataType: 'html',
                    success: function (response) {
                        $('#modal-title1').html('<h3>'+info.event.title+'</h3>');
                        $('#modal-body1').html(response);
                        if ($('#request_for').is('[disabled]')) {
                            $('#modal-btn-save1').hide();
                        }
                        $('#modal1').modal('show');
                    }
                });
            }
        });

        calendar.render();

        // ========= modal create ===========

        $('#create_form').on('click', function () {
            $('#modal-btn-save1').show();
            $.ajax({
                    url: "{{ route('mst.holiday.create') }}",
                    dataType: 'html',
                    success: function (response) {
                        $('#modal-title1').html('<h3>Request Form</h3>');
                        $('#modal-body1').html(response);
                        $('#modal1').modal('show');
                    }
            });
        });

        // ========= modal update ===========

        $('#modal-btn-save1').click(function(event) {
            event.preventDefault();

            var form = $('#modal-body1 form'),
                url = form.attr('action'),
                method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';

            form.find('.help-block').remove();
            form.find('.form-group').removeClass('has-error');  

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    Swal.fire('Please wait');
                    Swal.showLoading();
                    form.trigger('reset');
                    $('#modal1').modal('hide');
                    calendar.refetchEvents();
                    // alert(response);
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        html: '<strong style="color:green">Data saved successfully!</strong>'
                    });
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        $.each(res.errors, function(key, value) {
                            var id = $("*[name='" + key + "']").attr('id');
                            $('#' + id)
                                .addClass('is-invalid')
                                .closest('.form-group')
                                .addClass('has-error');

                            $('<span class="help-block invalid-feedback"><strong>' + value + '</strong></span>').insertAfter('#' + id);
                        });

                        Swal.fire({
                        type: 'error',
                        title: 'Error!',
                        html: '<strong style="color:red">'+res.message+'</strong>'
                    });
                    }
                }
            })
        });

        $('#_room_code').on("select2:select", function(e) { 

            var data = e.params.data;
            // console.log(data.id);

            calendar.destroy();
            calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                'themeSystem': 'bootstrap',
                minTime: "08:00",
                maxTime: "22:00",
                eventSources: [{
                    url: "{{ route('mst.holiday.search') }}",
                    method: 'POST',
                    extraParams: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        // room_code: $('#_room_code').find(':selected').data('value')
                        room_code: $('#_room_code').find(':selected').val()
                        // room_code: $('#_room_code').val()
                    },
                    failure: function() {
                        alert('there was an error while fetching events!');
                    },

                }],
                eventClick: function (info, jsEvent, view) {

                    $('#modal-btn-save1').show();
                    $.ajax({
                        url: "{{ route('mst.holiday.index') }}/"+info.event.id+"/edit",
                        dataType: 'html',
                        success: function (response) {
                            $('#modal-title1').html('<h3>'+info.event.title+'</h3>');
                            $('#modal-body1').html(response);
                            if ($('#request_for').is('[disabled]')) {
                                $('#modal-btn-save1').hide();
                            }
                            $('#modal1').modal('show');
                        }
                    });
                }
            })

            calendar.render();
        })
    })
</script>
@endpush