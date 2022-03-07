@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"> Payslip</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">Payroll</li>
                <li class="breadcrumb-item">Project</li>
                <li class="breadcrumb-item active">Payslip</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content-header -->
  
      <!-- Main content -->
      <div class="content">
        {{-- <div class="container"> --}}
          <div class="row">
            <div class="col-md-12">
                <div class="card">
                  @php
                  $dateObj   = DateTime::createFromFormat('!m', $model->payroll->month);
                  $monthName = $dateObj->format('F'); // March
              @endphp 
                  <div class="card-body" id="body">
                    <div class="row">
                      <div class="col-sm-6">
                        <center>
                          <img src="{{ asset('/img/emblem.png') }}" alt="MBPS Logo" class="brand-image" >
                          <h2><b>Mitracomm Ekasarana</b></h2>

                        </center>
                      </div>
                      <div class="col-sm-6">
                        <dl class="row">
                          <dt class="col-sm-6">{{ $model->employee->empl_id }}</dt>
                          <dt class="col-sm-6">{{ $model->payroll->group->name }}</dt>
                          <dt class="col-sm-6">{{ $model->employee->name }}</dt>
                          <dt class="col-sm-6">{{ $model->employee->project->name }}</dt>
                          <dt class="col-sm-6">{{ $monthName.' - '.$model->payroll->year }}</dt>
                          <dt class="col-sm-6">Kontrak</dt>
                        </dl>
                      </div>
                    </div>
                    <br>
                        <div class="row">
                          <div class="col-md-6">
                            <h4>Pendapatan</h4>
                            @php
                                $allowance = 0;
                                // $days = date('z', strtotime(date('y').'-12-31'))+1;
                            @endphp
                            <hr>
                                <dl class="row">
                                  <dt class="col-sm-4">&nbsp; Basic Salary</dt>
                                  <dd class="col-sm-4 text-md-right">Rp</dt>
                                  <dd class="col-sm-4 text-md-right">{{ number_format($model->basic_salary,0,',','.') }}</dd>
                                </dl>
                                <dl class="row">
                                  <dd class="col-sm-6">Fasilitas</dt>
                                  <dd class="col-sm-6"></dd>
                                  <dt class="col-sm-4">&nbsp; Tax Allowance</dt>
                                  <dd class="col-sm-4 text-md-right">Rp</dt>
                                  <dd class="col-sm-4 text-md-right">{{ number_format($model->tax_allowance,0,',','.') }}</dd>
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                  @if ($item->variable->type == 1)
                                      <dt class="col-sm-4">&nbsp; {{ $item->variable->name }}</dt>
                                         <dd class="col-sm-4 text-md-right">Rp</dt>
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                        @php
                                          $allowance += $item->value;
                                        @endphp
                                  @endif
                                @endforeach   
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                @if ($item->variable->type == 3)
                                    <dt class="col-sm-4">&nbsp; {{ $item->variable->name }}</dt>
                                    <dd class="col-sm-4 text-md-right">Rp</dt>
                                    <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                    @php
                                        $allowance += $item->value;
                                      @endphp
                                @endif
                              @endforeach       
                              </dl>
   
                 
                        </div>
                        <div class="col-md-6">
                            <h4>Pengurang</h4>
                            @php
                                $deduction = 0;
                            @endphp
                            <hr>
                                <dl class="row">
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                  @if ($item->variable->type == 2)
                                      <dt class="col-sm-4">&nbsp; {{ $item->variable->name }}</dt>
                                        <dd class="col-sm-4 text-md-right">Rp</dt>
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                        @php
                                          $deduction += $item->value;
                                        @endphp
                                  @endif
                                @endforeach 
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                @if ($item->variable->type == 4)
                                    <dt class="col-sm-4">&nbsp; {{ $item->variable->name }}</dt>
                                       <dd class="col-sm-4 text-md-right">Rp</dt>
                                      <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                      @php
                                        $allowance += $item->value;
                                      @endphp
                                @endif
                                @endforeach   
                                </dl>
                                <dl class="row">
                                  <dd class="col-sm-6">Potongan</dd>
                                  <dd class="col-sm-6 text-md-right"></dd>
  
                                  <dt class="col-sm-4">&nbsp; PPh 21</dt>
                                  <dd class="col-sm-4 text-md-right">Rp</dd>
                                  <dd class="col-sm-4 text-md-right">{{ number_format($model->pajak,0,',','.') }}</dd>

                                  <dt class="col-sm-4">&nbsp; Tax Pinalty</dt>
                                  <dd class="col-sm-4 text-md-right">Rp</dd>
                                  <dd class="col-sm-4 text-md-right">{{ number_format($model->tax_penalty,0,',','.') }}</dd>
                                </dl>   
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <dl class="row">
                          <dt class="col-sm-4">Sub Total Pendapatan</dt>
                          <dd class="col-sm-4 text-md-right">Rp</dd>
                          <dd class="col-sm-4 text-md-right">{{ number_format($allowance+$model->basic_salary+$model->tax_allowance,0,',','.') }}</dd>
                        </dl>
                        <dl class="row">
                          <dt class="col-sm-4">Pendapatan</dt>
                          <dd class="col-sm-4 text-md-right">Rp</dd>
                          <dd class="col-sm-4 text-md-right">{{ number_format($allowance+$model->basic_salary,0,',','.') }}</dd>

                          <dt class="col-sm-4">Pengurang</dt>
                          <dd class="col-sm-4 text-md-right">Rp</dd>
                          <dd class="col-sm-4 text-md-right">{{ number_format($deduction + $model->pajak + $model->tax_penalty,0,',','.') }}</dd>
                        </dl> 
                        <hr>
                        <dl class="row">
                            @php
                                $thp = $model->basic_salary + $allowance + $model->tax_allowance - $deduction - abs($model->pajak) - abs(round($model->tax_penalty));
                            @endphp                               
                          <dt class="col-sm-4">Total Gaji</dt>
                          <dd class="col-sm-4 text-md-right">Rp</dd>
                          <dd class="col-sm-4 text-md-right">{{ number_format(($thp),0,',','.') }}</dd>
                        </dl>  

                      </div>
                      <div class="col-md-6">
                        <dl class="row">
                          <dt class="col-sm-4">Sub Total Pengurang</dt>
                          <dd class="col-sm-4 text-md-right">Rp</dd>
                          <dd class="col-sm-4 text-md-right">{{ number_format($deduction + $model->pajak + $model->tax_penalty,0,',','.') }}</dd>
                        </dl>  
                        <dl class="row">
                          <dt class="col-sm-4">Nomor Jamsostek</dt>
                          <dt class="col-sm-8 text-md-right">xxxxxxxxxxxxxxx</dt>
                          <dt class="col-sm-4">Nomor BPJS Kesehatan</dt>
                          <dt class="col-sm-8 text-md-right">xxxxxxxxxxxxxxx</dt>
                          <dd class="col-sm-12">Gaji tersebut dalam Slip Pembayaran Gaji ini ditransfer ke : </dd>
                          <dt class="col-sm-12">BANK BCA No. Rekening : {{ $model->employee->bank_account }}</dt>
                        </dl>
                      </div>
                    </div>
                    <hr>
                    <small><b>PT. MITRACOMM EKASARANA</b> menyediakan lowongan kerja untuk: 1. Call Center, 2. Telesales Officer, 3. Direct Sales, 4. Sales Counter</small>
                  </div>
                </div>
            </div>
            </div>
          </div>
          <!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
      </div>
      <!-- /.content -->
@include('layouts._modal')

@push('scripts')
<script>
    
</script>    
@endpush

@endsection
