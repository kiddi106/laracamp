@extends('layouts.app')

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@if ($po->exists) Edit @else Input @endif Receive Purchase Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-inbox"></i> Receive Purchase Order</li>
                    @if ($po->exists)
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
<form action="{{ $po->exists ? route('po.update', ['id' => $po->id]) : route('po.store') }}" method="post">
    @csrf
    @if ($po->exists)
        <input name="_method" type="hidden" value="PUT">
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="row justify-content-center">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="po_no">No. Purchase Order</label>
                                <input type="text" class="form-control" id="po_no" name="po_no" placeholder="No Purchase Order" required @if ($po->exists) value="{{ $po->po_no }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="po_no">Purchase Date</label>
                                <input type="date" class="form-control" id="po_at" name="po_at" placeholder="dd/mm/yyyy" required @if ($po->exists) value="{{ $po->po_at }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="delivery_no">Delivery No.</label>
                                <input type="text" class="form-control" id="delivery_no" name="delivery_no" placeholder="Delivery No." required @if ($po->exists) value="{{ $po->delivery_no }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="receive_at">Delivery Date</label>
                                <input type="date" class="form-control" id="receive_at" name="receive_at" placeholder="dd/mm/yyyy" required @if ($po->exists) value="{{ $po->receive_at }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                @php
                                    $currencies = ['Rp' => 'Rupiah', '$' => 'Dollar'];
                                @endphp
                                <select class="form-control" id="currency" name="currency" @if ($po->exists) disabled @endif>
                                    @foreach ($currencies as $value => $text)
                                        <option value="{{ $value }}" @if ((!$po->exists && $value == 'Rp') || ($po->exists && $po->currency === $value)) selected @endif>{{ $text }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="div-kurs" @if (!$po->exists || ($po->exists && $po->currency === 'Rp')) style="display: none" @endif>
                                <label for="kurs">Kurs</label>
                                <input type="text" class="form-control number" id="_kurs" placeholder="Kurs" data-target="kurs" onkeyup="grandTotal()" @if($po->exists) value="{{ number_format($po->kurs, 0, ',', '.') }}" disabled @else 1 @endif>
                                <input type="hidden" id="kurs" name="kurs" value="{{ $po->exists ? $po->kurs : 1 }}" onkeyup="grandTotal()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Notes</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description ..." required>{{ $po->exists ? $po->description : '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail</h3>
            <div class="card-tools">
                @if (!$po->exists)
                <button type="button" id="btn_new_detail" class="btn btn-xs btn-success">New</button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="table_detail" class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">UoM</th>
                        <th class="text-center">Price <span class="valas">({{ $po->exists ? $po->currency: 'Rp' }})</span></th>
                        <th class="text-center">Total (Rp)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbody_detail">
                    @php
                        $grand_total = 0;
                    @endphp
                    @if ($po->exists)
                        @foreach ($po->dtls as $dtl)
                            @php
                                $grand_total += $dtl->total;
                            @endphp
                            <tr>
                                <td>{{ $dtl->material->type->name . '-' . $dtl->material->name }}</td>
                                <td class="text-right">{{ number_format($dtl->qty, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $dtl->uom }}</td>
                                <td class="text-right">{{ number_format($dtl->price, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($dtl->total, 0, ',', '.') }}</td>
                                <td>
                                    @if ($dtl->material->type_id == 1)
                                        <a href="{{ route('po.dtl.router.index', ['po_dtl_id' => $dtl->id]) }}" class="btn btn-xs btn-default" title="show" title="detailing"><i class="fas fa-table"></i> Detail</a>
                                    @elseif ($dtl->material->type_id == 2)
                                        <a href="{{ route('po.dtl.simcard.index', ['po_dtl_id' => $dtl->id]) }}" class="btn btn-xs btn-default" title="show" title="detailing"><i class="fas fa-table"></i> Detail</a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Grand Total</th>
                        <th id="grand_total" class="text-right">{{ number_format($grand_total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">{{ $po->exists ? 'Update' : 'Submit' }}</button>
</form>
@endsection

@section('modal')
<!-- modal-dialog -->
<div class="modal fade"role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Add Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="material_id">Item</label>
                    <select class="form-control" id="material_id"></select>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="text" class="form-control number" id="_qty" data-target="qty">
                    <input type="hidden" id="qty">
                </div>
                <div class="form-group">
                    <label for="uom">UoM</label>
                    <input type="text" class="form-control" id="uom" value="pcs">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="kurs_price"></span>
                        </div>
                        <input type="text" class="form-control number" id="_price" data-target="price">
                        <input type="hidden" id="price">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_add_detail">Add</button>
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
    function grandTotal() {
        $("#material_id").empty();
        $("#_qty").val('');
        $("#qty").val('');
        $("#uom").val('pcs');
        $("#_price").val('');
        $("#price").val('');

        let arr = [];
        $("#tbody_detail tr").each(function() {
            arr.push(this.id);
        });

        let kurs = 1;
        if ($('#currency').val() != 'Rp') {
            if ($("#kurs").val() == '') {
                $("#kurs").val('1');
                $("#_kurs").val('1');
            }
            kurs = parseInt($("#kurs").val());
        } else {
            $("#kurs").val('1');
            $("#_kurs").val('1');
        }

        let grandTotal = 0;
        arr.forEach((id) => {
            const qty = parseInt($('[name^="po_dtls[' + id + '][qty]"]').val());
            const price = parseInt($('[name^="po_dtls[' + id + '][price]"]').val());
            const total = qty * price * kurs;
            $('#' + id + '_total').html(formatRupiah(total.toString()));
            $('[name^="po_dtls[' + id + '][total]"]').val(total);
            grandTotal += total;
        });
        $("#grand_total").html(formatRupiah(grandTotal.toString()));
    }

    function deleteRow(id) {
        $("#" + id).remove();
        grandTotal();
    }

    $(() => {
        $('#currency').change(() => {
            $('#div-kurs').hide();
            $('#kurs').val('1');
            const currency = $('#currency').val();
            if (currency != 'Rp') {
                $('#div-kurs').show();
            }
            $('.valas').text('(' + currency + ')');
            grandTotal();
        });

        $('#btn_new_detail').click(() => {
            const currency = $('#currency').val();
            $('#kurs_price').html(currency);

            $("#material_id").empty();
            $("#_qty").val('');
            $("#qty").val('');
            $("#uom").val('pcs');
            $("#_price").val('');
            $("#price").val('');
            $("#modal_detail").modal("show");
        });

        $('#material_id').select2({
            placeholder: 'Search ...',
            ajax: {
                url: "{{ route('material.load_data') }}",
                method: "POST",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.type_name + '-' + item.name,
                                id: item.id,
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $("#btn_add_detail").click(() => {
            const material = {
                value: $("#material_id").val(),
                text: $("#material_id option:selected").text()
            }
            const id = (new Date()).getTime();
            const qty = parseInt($('#qty').val());
            let html = '<tr id="' + id + '">';
            html += '<td>'
                + '<span id="' + id + '_item">' + material.text + '</span>'
                + `<input type="hidden" name="po_dtls[${id}][material_id]" value="${material.value}"/>`
                + '</td>';
            html += '<td class="text-right">'
                + '<span id="' + id + '_qty">' + $("#_qty").val() + '</span>'
                + `<input type="hidden" name="po_dtls[${id}][qty]" value="${qty}"/>`
                + '</td>';
            html += '<td class="text-center">'
                + '<span id="' + id + '_uom">' + $("#uom").val() + '</span>'
                + `<input type="hidden" name="po_dtls[${id}][uom]" value="${$('#uom').val()}"/>`
                + '</td>';

            const price = parseInt($("#price").val());
            const kurs = parseInt($("#kurs").val());
            let total = price * qty;
            if ($('#currency').val() != 'Rp') {
                total = total * kurs;
            }
            html += '<td class="text-right">'
                + '<span id="' + id + '_price">' + $("#_price").val() + '</span>'
                + `<input type="hidden" name="po_dtls[${id}][price]" value="${price}"/>`
                + '</td>';
            html += '<td class="text-right">'
                + '<span id="' + id + '_total">' + formatRupiah(total.toString()) + '</span>'
                + `<input type="hidden" name="po_dtls[${id}][total]" value="${total}"/>`
                + '</td>';

            html += '<td class="text-center">'
                + '<button type="button" class="btn btn-xs btn-danger" onclick="deleteRow(\'' + id + '\')"><i class="fas fa-trash"></i></button>'
                + '</td>';

                html += '</tr>';
            $("#tbody_detail").append(html);
            $("#modal_detail").modal("hide");
            grandTotal();
        });
    });
</script>
@endpush
