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
@endpush

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark"> Req - Room</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Req</li>
                <li class="breadcrumb-item">Room</li>
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
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="area" class="col-sm-2 col-form-label">{{ __('Area') }}</label>
                            <div class="col-sm-6">
                            <select id="area" name="area" class="select2bs4" style="width: 100%;">
                                    <option value="">Select Area</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->area_code }}">
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gedung" class="col-sm-2 col-form-label">{{ __('Building') }}</label>
                            <div class="col-sm-6">
                                <select id="gedung" class="select2bs4" style="width: 100%;">
                                <option value="">Select Building</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="_room_code" class="col-sm-2 col-form-label">{{ __('Room') }}</label>
                            <div class="col-sm-6">
                                <select id="_room_code" class="select2bs4" style="width: 100%;">
                                <option value="">Select Location</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    @if(Auth::user()->can('create-req-room'))
                    <input type="button" id="create_form" class="btn btn-success float-right" value="New Request">
                    @endif
                    {{-- <button class="btn btn-success modal-show float-right" onclick="createForm()">New Request</button> --}}
                </div>
            </div>
            <hr>
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
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
<script>

    $(function () {
        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');
        var calendar = new Calendar(calendarEl, {
            plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
            defaultView: 'timeGridWeek',
            height: 'auto',
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            minTime: "08:00",
            maxTime: "23:00",
            'themeSystem': 'bootstrap',
            eventSources: [{
                url: "{{ route('ga.room.search') }}",
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
                    url: "{{ route('ga.room.index') }}/"+info.event.id+"/edit",
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
                    url: "{{ route('ga.room.create') }}",
                    dataType: 'html',
                    success: function (response) {
                        $('#modal-title1').html('<h3>Request Form Meeting</h3>');
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

        // ========= Get Area ===========

        $('#area').change( () => {
            Swal.showLoading();
            var area = $('#area').val();
            $('#gedung').empty();
            $.ajax({
                url : '{{ route("ga.room.getArea") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType : 'json',
                data: {
                    area
                },
                success: (data) => {
                    var str_data = '<option value="">Select Building</option>';
                    str_data += data.map((gedung) => {
                        return '<option value="'+gedung.gedung+'">'+gedung.description+'</option>';
                    });
                    $('#gedung').append(str_data);
                    Swal.close();
                },
                error: (xhr) => {
                    console.log(xhr);
                    Swal.close();
                }
            });
        });

        // -------- GET BUILDING ----------

        $('#gedung').change( () => {
            Swal.showLoading();
            var gedung = $('#gedung').val();
            $('#_room_code').empty();
            $.ajax({
                url : '{{ route("ga.room.getBuilding") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType : 'json',
                data: {
                    gedung
                },
                success: (data) => {
                    var str_data = '<option value="">Select Room</option>';
                    str_data += data.map((room) => {
                        return '<option value="'+room.code+'">'+room.name+'</option>';
                    });
                    $('#_room_code').append(str_data);
                    Swal.close();
                },
                error: (xhr) => {
                    console.log(xhr);
                    Swal.close();
                }
            });
        });


        $('#_room_code').on("select2:select", function(e) { 

            var data = e.params.data;
            // console.log(data.id);

            calendar.destroy();
            calendar = new Calendar(calendarEl, {
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
                defaultView: 'timeGridWeek',
                header    : {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                'themeSystem': 'bootstrap',
                height: 'auto',
                minTime: "08:00",
                maxTime: "23:00",
                eventSources: [{
                    url: "{{ route('ga.room.search') }}",
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
                        url: "{{ route('ga.room.index') }}/"+info.event.id+"/edit",
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