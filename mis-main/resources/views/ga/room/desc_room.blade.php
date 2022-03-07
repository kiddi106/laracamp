<form action="{{ $model->exists ? route('ga.room.update', base64_encode($model->id)) : route('ga.room.store') }}" method="POST">
    @csrf
    @if ($model->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <label for="req_start" class="col-md-3 col-form-label text-md-right">{{ __('Date & Time') }}</label>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="_req_start"
                                 @if ($model->exists)
                                     value="{{ date('d/m/Y H:i', strtotime($model->req_start)) }}"
                                 @endif
                                 autofocus
                                 {{ $disabled }}
                            >
                            <input type="hidden" name="req_start"
                                 @if ($model->exists)
                                     value="{{ $model->req_start }}"
                                 @endif
                            >
                        </div>
                        <div class="col-md-2 text-center"> 
                            {{-- To --}}
                            <p style="margin-top: 8px"> To </p>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="_req_end"
                                 @if ($model->exists)
                                     value="{{ date('d/m/Y H:i', strtotime($model->req_end)) }}"
                                 @endif
                                {{ $disabled }}
                            >
                            <input type="hidden" name="req_end"
                                 @if ($model->exists)
                                     value="{{ $model->req_end }}"
                                 @endif
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="request_for" class="col-md-3 col-form-label text-md-right">{{ __('Req. Name') }}</label>
                <div class="col-md-8">
                    <select name="request_for" id="request_for" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>
                        @if (!$model->exists)
                            <option value="">Please Select Req. Name</option>
                        @endif
                        
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->uuid }}"
                                @if ($model->exists && $employee->uuid === $model->request_for)
                                    SELECTED
                                @endif
                            title="{{ $employee->ext_no }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>

                    @error('request_for')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

           

            <div class="form-group row">
                <label for="phone" class="col-md-3 col-form-label text-md-right">{{ __('Phone Number') }}</label>
                <div class="col-md-8">
                    <input type="text" name="phone" id="phone" class="form-control @error('ext') is-invalid @enderror"
                        @if ($model->exists)
                            value="{{ $model->phone }}"
                        @else
                            @error('phone') value="{{ old('phone') }}" @enderror
                        @endif
                        placeholder="0812132XXX"
                        autofocus
                        {{ $disabled }}>

                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="dept_code" class="col-md-3 col-form-label text-md-right">{{ __('Department') }}</label>
                <div class="col-md-8">
                    <select name="dept_code" id="dept_code" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>
                        @if (!$model->exists)
                            <option value="">Please Select Department</option>
                        @endif
                        @foreach ($departments as $department)
                            <option value="{{ $department->code }}"
                                @if ($model->exists && $department->code === $model->dept_code)
                                    SELECTED
                                @endif
                            >{{ $department->name }}</option>
                        @endforeach
                        {{ $disabled }}
                    </select>

                    @error('dept_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="area_code" class="col-md-3 col-form-label text-md-right">{{ __('Area') }}</label>
                <div class="col-md-8">
                    <select name="area_code" id="area_code" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>
                        @if (!$model->exists)
                            <option value="">Please Select Area</option>
                        @endif
                        @foreach ($areas as $area)
                            <option value="{{ $area->area_code  }}" {{ $check = ($model->exists) ? ($area->area_code == $model->room->area) ? 'selected' : '' : '' }}>{{ $area->name }}</option>
                        @endforeach
                        {{ $disabled }}
                    </select>

                    @if(\Session::has('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! \Session::get('error') !!}</strong>
                        </span>
                    @endif

                    @error('area_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="gedung_code" class="col-md-3 col-form-label text-md-right">{{ __('Building') }}</label>
                <div class="col-md-8">
                    <select name="gedung_code" id="gedung_code" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>
                        
                        <option value="">Please Select Building</option>
                        @foreach (App\Models\Mst\MstArea::All() as $gedung)
                            <option value="{{ $gedung->gedung  }}" @if($model->exists &&
                            $model->room->gedung
                            ===
                            $gedung->gedung) SELECTED @endif>{{ $gedung->description }}</option>
                        @endforeach

                        {{ $disabled }}
                    </select>

                    @if(\Session::has('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! \Session::get('error') !!}</strong>
                        </span>
                    @endif
                    @error('gedung_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="room_code" class="col-md-3 col-form-label text-md-right">{{ __('Room') }}</label>

                <div class="col-md-8">
                    <select name="room_code" id="room_code" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>

                    <option value="">Please Select Room</option>
                        @foreach (App\Models\Mst\MstArea::All() as $room)
                            <option value="{{ $room->code  }}" @if($model->exists &&
                            $model->room->code
                            ===
                            $room->code) SELECTED @endif>{{ $room->name }}</option>
                        @endforeach

                        {{ $disabled }}
                    </select>

                    

                    @if(\Session::has('error'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! \Session::get('error') !!}</strong>
                        </span>
                    @endif
                    @error('room_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="purpose" class="col-md-3 col-form-label text-md-right">{{ __('Purpose') }}</label>
                <div class="col-md-8">
                   <textarea name="purpose" id="purpose" cols="30" class="form-control" style="width: 100%; font-size:14px" {{ $disabled }}>@if ($model->exists){{ $model->purpose }} @else @error('purpose'){{ old('purpose') }}@enderror @endif</textarea>

                    @error('purpose')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                </div>
            </div>

            @if ($model->exists)
            <div class="form-group row">
                <label for="room_status" class="col-md-3 col-form-label text-md-right">{{ __('Status') }}</label>
                <div class="col-md-8">
                    <select name="room_status" id="room_status" class="form-control select2bs4" style="width: 100%" {{ $disabled }}>
                            <option value="1"
                                @if ($model->status_id == 1)
                                    selected
                                @endif>Booked</option>
                            <option value="2"
                                @if ($model->status_id == 2)
                                    selected 
                                @endif>Cancelled</option>
                    </select>

                    @error('room_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

<script>
    $(function() {
         $('.select2bs4').select2({
            theme: 'bootstrap4'
         }) 

         $('#request_for').on("select2:select", function(e) { 
            var data = e.params.data;
             $('#ext').val(data.title);
        });
        getArea();
        // getBuilding();
    // ========= Get Area ===========

    function getArea() {
        Swal.showLoading();
        var area = $('#area_code').val();
        $('#gedung_code').empty();
        $.ajax({
            url : '{{ route('ga.room.getArea') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                area
            },
            success: (data) => {
                var str_data = '<option value="">Please Select Building</option>';
                str_data += data.map((gedung) => {
                    return '<option value="'+gedung.gedung+'">'+gedung.description+'</option>';
                });
                $('#gedung_code').append(str_data);
                getBuilding();
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    }
    $('#area_code').change( () => {
        getArea();
        // getBuilding();
    });  

    // -------- GET BUILDING ----------
    function getBuilding() {
        Swal.showLoading();
        var gedung = $('#gedung_code').val();
        // console.log(gedung, roomCode)
        $('#room_code').empty();
        $.ajax({
            url : '{{ route('ga.room.getBuilding') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                gedung
            },
            success: (data) => {
                var str_data = '<option value="">Please Select Room</option>';
                str_data += data.map((room) => {
                    return '<option value="'+room.code+'"> '+room.name+' '+room.capacity+'</option>';                        
                });
                $('#room_code').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    }
    $('#gedung_code').change( () => {
        getBuilding();
    }); 
    
    $("#_req_start").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 15,
            minDate: new Date(),
            locale: {
                format: 'DD/MM/YYYY HH:mm'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            $('[name^="' + $(this).attr('id').replace('_', '') + '"]').val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });
        $("#_req_end").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 15,
            minDate: new Date(),
            locale: {
                format: 'DD/MM/YYYY HH:mm'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm'));
            $('[name^="' + $(this).attr('id').replace('_', '') + '"]').val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });
    })
</script>

