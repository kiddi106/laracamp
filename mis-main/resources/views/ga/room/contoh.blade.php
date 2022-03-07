{{-- @extends('layouts.app')

@include('plugins.daterangepicker')
@include('plugins.fullcalendar')
@include('plugins.select2')

@section('content-header')
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
</div>
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <form class="form-horizontal">
                        <div class="form-group row">
                            <label for="_room_code" class="col-sm-2 col-form-label">Room</label>
                            <div class="col-sm-10">
                                <select id="_room_code" class="select2bs4" style="width: 100%;">
                                    @foreach ($rooms as $room)
                                    <option value="{{ $room->code }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal1">
                        New Request
                      </button>
                </div>
            </div>
            <hr>
            <div id="calendar"></div>
        </div>
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
    </div>
</div>


@endsection

@push('scripts')
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
                url: "{{ route('ga.room.search') }}",
                method: 'POST',
                extraParams: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    room_code: $('#_room_code').val()
                },
                failure: function() {
                    alert('there was an error while fetching events!');
                },
            }],
            eventClick: function (info, jsEvent, view) {

                $.ajax({
                    url: "{{ route('ga.room.index') }}/"+info.event.id+"/edit",
                    dataType: 'html',
                    success: function (response) {
                        $('#modal-title1').html('<h3 style="font-size: 30px">'+info.event.title+'</h3>');
                        $('#modal-body1').html(response);
                        $('#modal1').modal('show');
                    }

                });

                
            }
        });

        calendar.render();

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
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data has been saved!'
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
                        text: res.message   
                    });
                    }
                }
            })
        });
    })
</script>
@endpush --}}