@extends('layouts.app', ['title' => 'Pickpack - ' . ($model->exists ? 'Edit' : 'Input')])

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-table"></i> Pickpack - @if ($model->exists) Update @else Input @endif</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-box"></i> Pickpack</li>
                    @if ($model->exists)
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit</li>
                    @else
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Input</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
{{-- <div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">bs-stepper</h3>
            </div>
            <div class="card-body p-0">
                <div class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <!-- your steps here -->
                        <div class="step" data-target="#logins-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="logins-part"
                                id="logins-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Logins</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="information-part"
                                id="information-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Various information</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <!-- your steps content here -->
                        <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1"
                                    placeholder="Password">
                            </div>
                            <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                        </div>
                        <div id="information-part" class="content" role="tabpanel"
                            aria-labelledby="information-part-trigger">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                Visit <a href="https://github.com/Johann-S/bs-stepper/#how-to-use-it">bs-stepper documentation</a> for
                more examples and information about the plugin.
            </div>
        </div>
        <!-- /.card -->
    </div>
</div> --}}
<form action="{{ $model->exists ? route('pickpack.update', $model->id) : route('pickpack.store') }}" method="POST" id="frm">
    {{ csrf_field() }}
    @if ($model->exists)
    <input type="hidden" name="_method" value="PUT">
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @if ($model->exists)
                    <div class="card-header">
                        <h3 class="card-title">{{ $model->order_number }} <span class="right badge {{ $model->status->bgcolor }}">{{ $model->status->name }}</span></h3>
                        <input type="hidden" name="status_id" id="status_id" value="{{ $model->status_id }}">

                        <div class="float-right">
                            <a href="{{ route('pickpack.print_awb', ['id' => $model->id]) }}" class="btn btn-default" target="_blank"><i class="fas fa-barcode"></i> Print AWB</a>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Order Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="order_number">Order Number</label>
                                        <input type="text" class="form-control" id="order_number" @if ($model->exists) value="{{ $model->order_number }}" @endif placeholder="automated" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="purchase_type_id">Purchase Type</label>
                                        <select name="purchase_type_id" id="purchase_type_id" class="select2bs4 form-control" style="width: 100%;">
                                            @foreach (App\Models\PurchaseType::all() as $type)
                                                <option value="{{ $type->id }}" @if ($model->exists && $model->purchase_type_id == $type->id) selected @endif>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="document_number">Document Number</label>
                                        <input type="text" class="form-control" id="document_number" name="document_number" @if ($model->exists) value="{{ $model->document_number }}" @endif placeholder="Document Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="awb">AWB</label>
                                        <input type="text" class="form-control" id="awb" name="awb" @if ($model->exists) value="{{ $model->awb }}" @endif placeholder="Airway Bill">
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control">{{ $model->exists ? $model->notes : '' }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Customer Information</h3>
                                    @if ($model->exists)
                                        <input type="hidden" class="form-control" name="receiver[id]" value="{{ $model->receiver->id }}">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="customer_number">Customer Number</label>
                                        <input type="text" class="form-control" id="customer_number" name="customer_number" @if ($model->exists) value="{{ $model->customer_number }}" @endif placeholder="Customer Number">
                                    </div>

                                    <div class="form-group">
                                        <label for="receiver_name">Customer Name</label>
                                        <input type="text" class="form-control" id="receiver_name" name="receiver[name]" @if ($model->exists) value="{{ $model->receiver->name }}" @endif placeholder="Customer Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="receiver_phone">Phone Number</label>
                                        <input type="text" class="form-control" id="receiver_phone" name="receiver[phone]" @if ($model->exists) value="{{ $model->receiver->phone }}" @endif placeholder="08xxx">
                                    </div>

                                    <div class="form-group">
                                        <label for="receiver_postal_code">Postal Code</label>
                                        <input type="text" class="form-control" id="receiver_postal_code" name="receiver[postal_code]" @if ($model->exists) value="{{ $model->receiver->postal_code }}" @endif placeholder="123456">
                                    </div>

                                    <div class="form-group">
                                        <label for="receiver_destination">Destination</label>
                                        <textarea name="receiver[destination]" class="form-control">{{ $model->exists ? $model->receiver->destination : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Delivery Information</h3>
                                    @if ($model->exists)
                                        <input type="hidden" class="form-control" name="delivery[id]" value="{{ $model->delivery->id }}">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="delivery_type">Delivery Name</label>
                                        <input type="text" class="form-control" id="delivery_type" name="delivery[type]" @if ($model->exists) value="{{ $model->delivery->type }}" @endif placeholder="JNE">
                                    </div>

                                    <div class="form-group">
                                        <label for="delivery_do_date">DO Date</label>
                                        <input type="datetime-local" class="form-control" id="delivery_do_date" name="delivery[do_date]" @if ($model->exists) value="{{ date('Y-m-d\TH:i:s', strtotime($model->delivery->do_date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                            @if ($model->exists && $model->received_by)
                                <div class="card">
                                    <div class="card-header bg-lightblue">
                                        <h3 class="card-title">Received Order Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <dl class="row">
                                            <dt class="col-sm-4">Received by</dt>
                                            <dd class="col-sm-8">{{ $model->received_user->name }}</dd>
                                            <dt class="col-sm-4">Received at</dt>
                                            <dd class="col-sm-8">{{ date('d/M/Y', strtotime($model->received_at)) }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($model->pick_up_id)
                            <div class="col-sm">
                                <div class="card">
                                    <div class="card-header bg-gray">
                                        <h3 class="card-title">Pick Up Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="pick_up_number">Pick Up Number</label>
                                            <input type="text" class="form-control" id="pick_up_number" value="{{ $model->pick_up->pick_up_number }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="pick_up_name">Name</label>
                                            <input type="text" class="form-control" id="pick_up_name" name="pick_up[name]" value="{{ $model->pick_up->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pick_up_driver">Driver</label>
                                            <input type="text" class="form-control" id="pick_up_driver" name="pick_up[driver]" value="{{ $model->pick_up->driver }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pick_up_vehicle">Vehicle</label>
                                            <input type="text" class="form-control" id="pick_up_vehicle" name="pick_up[vehicle]" value="{{ $model->pick_up->vehicle }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pick_up_police_no">Police No.</label>
                                            <input type="text" class="form-control" id="pick_up_police_no" name="pick_up[police_no]" value="{{ $model->pick_up->police_no }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="pick_up_picked_at">Picked At</label>
                                            <input type="datetime-local" class="form-control" id="pick_up_picked_at" name="pick_up[picked_at]" @if ($model->pick_up->picked_at) value="{{ date('Y-m-d\TH:i', strtotime($model->pick_up->picked_at)) }}" @endif>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div class="float-right">
                        @if (!$model->exists)
                            <button type="submit" class="btn btn-warning">Request</button>
                        @else
                            @if ($model->status_id == 1)
                                <button type="button" class="btn bg-lightblue btn-submit" onclick="updateStatus(3)">Receive</button>
                            @elseif ($model->status_id == 3)
                                <button type="button" class="btn bg-info btn-submit" onclick="updateStatus(4)">Ready to Pick Up</button>
                            @elseif ($model->status_id == 4)
                                <button type="button" class="btn bg-success btn-submit" onclick="updateStatus(6)">Picked Up</button>
                            @endif
                            <button type="submit" class="btn btn-primary">Update</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@if ($model->exists)
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail - Order</h3>
            </div>
            <div class="card-body">
                <table id="datatable-dtl" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 30px"></th>
                            <th class="no-sort" style="width: 30px">No</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th class="no-sort" style="width: 30px"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@section('modal')
<!-- modal-dialog -->
<div class="modal fade" role="dialog" aria-labelledby="modal" aria-hidden="true" id="modalItemOrbit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalItemOrbit-title">Add</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalItemOrbit-body">
                <form action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="_method" id="_order_item_method" value="">
                    <input type="hidden" name="order_item_id" id="_order_item_id" value="">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="_orbit_stock_id">Orbit Stock</label>
                                <select class="form-control" id="_orbit_stock_id" name="orbit_stock_id"></select>
                                <!-- /input-group -->
                            </div>
                        </div>
                        <div class="col-12" id="stock-info" style="display: none;">
                            <div class="row">
                                <div class="col">
                                    <div id="router-info">
                                        Router Info
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <dl>
                                                    <dt>ESN</dt>
                                                    <dd id="_stock_esn"></dd>
                                                    <dt>SSID</dt>
                                                    <dd id="_stock_ssid"></dd>
                                                    <dt>Password Router</dt>
                                                    <dd id="_stock_password_router"></dd>
                                                    <dt>Guest SSID</dt>
                                                    <dd id="_stock_guest_ssid"></dd>
                                                    <dt>Password Admin Web</dt>
                                                    <dd id="_stock_password_admin"></dd>
                                                </dl>
                                            </div>
                                            <div class="col-sm-6">
                                                <dl>
                                                    <dt>IMEI</dt>
                                                    <dd id="_stock_imei"></dd>
                                                    <dt>Device Model</dt>
                                                    <dd id="_stock_device_model"></dd>
                                                    <dt>Device Type</dt>
                                                    <dd id="_stock_device_type"></dd>
                                                    <dt>Color</dt>
                                                    <dd id="_stock_color"></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="simcard-info">
                                        Simcard Info
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <dl>
                                                    <dt>SN</dt>
                                                    <dd id="_stock_serial_no"></dd>
                                                    <dt>MSISDN</dt>
                                                    <dd id="_stock_msisdn"></dd>
                                                </dl>
                                            </div>
                                            <div class="col-sm-6">
                                                <dl>
                                                    <dt>Item Code</dt>
                                                    <dd id="_stock_item_code"></dd>
                                                    <dt>Expired</dt>
                                                    <dd id="_stock_exp_at"></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnItemOrbit">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal-dialog -->
@endsection

@push('scripts')
    <script>
        let orbitStocks = [];
        const cols = ['esn','ssid','password_router','guest_ssid','password_admin','imei','device_model','device_type','color','serial_no','msisdn','item_code','exp_at'];
        function updateStatus(status_id) {
            $("#status_id").val(status_id);
            $("#frm").submit();
        }

        function resetOrbitItem() {
            $("#_orbit_stock_id").val(null).trigger('change');
            cols.forEach((col, idx) => {
                $("#_stock_" + col).html('-');
            });
            $("#btnItemOrbit").text('Add');
            $('#modalItemOrbit-title').text('Add');
            $("#_order_item_method").val('');
            orbitStocks = [];
        }

        function addOrbitItem(order_item_id) {
            resetOrbitItem();
            $("#_order_item_id").val(order_item_id);
            $("#modalItemOrbit").modal("show");
            $("#_order_item_method").val('');
            $("#modalItemOrbit-body form").attr('action', "{{ route('pickpack.dtl.item.store') }}");
        }

        function editOrbitItem(order_item_id, order_item_orbit_id, ssid) {
            resetOrbitItem();
            $("#_order_item_id").val(order_item_id);
            $("#btnItemOrbit").text('Update');
            $('#modalItemOrbit-title').text('Edit');
            $("#_order_item_method").val('PUT');
            $("#modalItemOrbit-body form").attr('action', "{{ route('pickpack.dtl.item.store') }}" + '/' + order_item_orbit_id);
            $("#modalItemOrbit").modal("show");
            $("#_orbit_stock_id").val(null).trigger('change');
        }

        $(() => {
            let detailRows = [];
            let dt = $('#datatable-dtl').DataTable({
                paging: true,
                searching: false,
                ordering: true,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                cache: false,
                ajax: {
                    url: "{{ route('pickpack.dtl.datatable') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function ( d ) {
                        return $.extend( {}, d, {
                            order_id: {{ $model->id }},
                        });
                    }
                },
                columns: [
                    {
                        "class": "details-control text-center",
                        "orderable": false,
                        "data": null,
                        "defaultContent": '<button type="button" class="btn btn-xs btn-default"><i class="fas fa-th-list"></i></button>'
                    },
                    {data: 'DT_RowIndex', name: 'id', class: 'text-center'},
                    {data: 'sku', name: 'sku'},
                    {data: 'qty', name: 'qty'},
                    {data: 'action', name: 'action'}
                ],
                columnDefs: [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                }],
                order: [[ 2, "asc" ]]
            }).on('draw', function () {
                $('td.details-control').trigger( 'click' );
            });

            $('#datatable-dtl tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = dt.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );

                    Swal.fire('Please wait');
                    Swal.showLoading();
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('pickpack.dtl.show.items', ['']) }}/" + row.data().id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (data) => {
                            row.child( data, 'active' ).show();
                            // Add to the 'open' array
                            if ( idx === -1 ) {
                                detailRows.push( tr.attr('id') );
                            }
                            Swal.close();
                        },
                        error: (xhr) => {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong! ',
                                text: 'Please contact Administrator'
                            });
                        }
                    });
                }
            });

            $('#_orbit_stock_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Search by imei...',
                ajax: {
                    url: "{{ route('orbitstock.load_data') }}",
                    method: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            imei: params.term,
                            status_id: 2,
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        }
                    },
                    processResults: function (data) {
                        orbitStocks = data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.imei + ' ' + item.material_name,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: false
                }
            });

            $("#_orbit_stock_id").on('select2:select', function (e) {
                $("#stock-info").hide();
                if (Array.isArray(orbitStocks) && orbitStocks.length === 1) {
                    const orbitStock = orbitStocks[0];
                    cols.forEach((col, idx) => {
                        if (orbitStock[col]) {
                            $("#_stock_" + col).html(orbitStock[col]);
                        } else {
                            $("#_stock_" + col).html('-');
                        }
                    });
                    $("#stock-info").show();
                }
            });

            $("#btnItemOrbit").click(() => {
                var form = $("#modalItemOrbit-body form"),
                    url = form.attr('action'),
                    method = $("#_order_item_method").val() == '' ? 'POST' : 'PUT';
                console.log({url, method});

                form.find('.help-block').remove();
                form.find('.form-group').removeClass('has-error');

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function (response) {
                        resetOrbitItem();
                        $('#modalItemOrbit').modal('hide');
                        $('#datatable-dtl').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Data has been saved!',
                            timer: 2000,
                            confirmButtonColor: '#3085d6',
                        });
                    },
                    error: function (xhr) {
                        var res = xhr.responseJSON;
                        if ($.isEmptyObject(res) == false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.msg,
                                confirmButtonColor: '#3085d6',
                            });
                            $.each(res, function (key, value) {
                                $('#' + key)
                                    .closest('.form-group')
                                    .addClass('has-error')
                                    .append('<span class="help-block"><strong>' + value + '</strong></span>');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush

@endif

@endsection
