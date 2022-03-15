@extends('layouts.app', ['title' => 'Orders', 'collapse' => false])

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-table"></i> Orders</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-table"></i> Orders</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
          <!-- title row -->
          <div class="row">
            <div class="col-12">
              <h4>
                {{ $model->order_number }}
                <span class="right badge {{ $model->status->bgcolor }}">{{ $model->status->name }}</span>
                <small class="float-right">Date: {{ date('d/M/Y H:i:s', strtotime($model->created_at)) }}</small>
              </h4>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-3 invoice-col">
                <h5>Order Information</h5><br>
                <b>Purchase Type : </b> {{ $model->purchase_type->name }}<br>
                <b>Document Number : </b> {{ $model->document_number }}<br>
                <b>AWB : </b> {{ $model->awb }}<br>
                <b>Notes : </b> {{ $model->notes }}<br>
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <h5>Customer Information</h5><br>
                <b>Customer Number : </b> {{ $model->customer_number }}<br>
                <b>Customer Name : </b> {{ $model->receiver->name }}<br>
                <b>Phone Number : </b> {{ $model->receiver->phone }}<br>
                <b>Postal Code : </b> {{ $model->receiver->postal_code }}<br>
                <b>Destination : </b><br>
                <address>
                    {{ $model->receiver->destination }}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
              @if ($model->pick_up)
                <h5>Pick Up Information</h5><br>
                <b>Pick Up Number : </b> {{ $model->pick_up->pick_up_number }}<br>
                <b>Name : </b> {{ $model->pick_up->name }}<br>
                <b>Driver : </b> {{ $model->pick_up->driver }}<br>
                <b>Vehicle : </b> {{ $model->pick_up->vehicle }}<br>
                <b>Police No : </b> {{ $model->pick_up->police_no }}<br>
                <b>Picked At : </b> {{ date('d/M/Y H:i:s', strtotime($model->pick_up->picked_at)) }}<br>
              @endif
            </div>
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
              <h5>Delivery Information</h5><br>

                <b>Delivery Name : </b> {{ $model->delivery->type }}<br>
                <b>Delivery Date: </b> {{ date('d/M/Y H:i:s', strtotime($model->delivery->do_date)) }}<br>
                <br>
              @if ($model->received_by)
              <h5>Received Order Information</h5><br>

                <b>Received Order Name : </b> {{ $model->received_user->name }}<br>
                <b>Received Order Date: </b> {{ date('d/M/Y H:i:s', strtotime($model->created_at)) }}<br>
              @endif
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- Table row -->
          <div class="row">
            <div class="col-12 table-responsive">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>SKU</th>
                  <th>QTY</th>
                  <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($model->items as $key =>$value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value->sku }}</td>
                        <td>{{ $value->qty }}</td>
                        <td>
                            @foreach ($value->items as $item)
                                <li>
                                    SSID: {{ $item->orbitStock->router->ssid }}<br>
                                    IMEI: {{ $item->orbitStock->router->imei }}<br>
                                    MSISDN: {{ $item->orbitStock->simcard->msisdn }}<br>
                                </li>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach

                </tbody>
              </table>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- this row will not appear when printing -->
          {{-- <div class="row no-print">
            <div class="col-12">
              <button onclick="window.print()" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
            </div>
          </div> --}}
        </div>
        <!-- /.invoice -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
@endsection

