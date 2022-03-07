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
                  <div class="card-header">
                    @php
                        $dateObj   = DateTime::createFromFormat('!m', $model->payroll->month);
                        $monthName = $dateObj->format('F'); // March
                    @endphp 
                    <h4>{{ $model->employee->name }}</h4>
                    <p><b>{{ $model->payroll->group->name }}</b> {{ $monthName.' - '.$model->payroll->year }}</p>
                  </div>
                  <div class="card-body" id="body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Allowance</h4>
                            @php
                                $allowance = 0;
                                $days = date('z', strtotime(date('y').'-12-31'))+1;
                            @endphp
                            <hr>
                                <dl class="row">
                                  <dt class="col-sm-4">Basic Salary</dt>
                                  <dd class="col-sm-8 text-md-right">{{ number_format($model->basic_salary,0,',','.') }}</dd>
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                  @if ($item->variable->type == 1)
                                      <dt class="col-sm-4">{{ $item->variable->name }}</dt>
                                      @if ($item->variable->counter == 1)
                                        <dd class="col-sm-4 text-md-right">{{ '('.number_format($item->value,0,',','.').' x '.$days.')' }}</dt>
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value * $days,0,',','.') }}</dd>
                                        @php
                                            $allowance += $item->value;
                                        @endphp
                                      @else
                                         <dd class="col-sm-4 text-md-right"></dt>{{--{{ '('.number_format($item->value,0,',','.').' x 12)' }}</dt> --}}
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                        @php
                                          $allowance += $item->value;
                                        @endphp
                                      @endif
                                  @endif
                                @endforeach   
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                @if ($item->variable->type == 3)
                                    <dt class="col-sm-4">{{ $item->variable->name }}</dt>
                                    @if ($item->variable->counter == 1)
                                      <dd class="col-sm-4 text-md-right">{{ '('.number_format($item->value,0,',','.').' x '.$days.')' }}</dt>
                                      <dd class="col-sm-4 text-md-right">{{ number_format($item->value * $days,0,',','.') }}</dd>
                                      @php
                                          $allowance += $item->value;
                                      @endphp
                                    @else
                                       <dd class="col-sm-4 text-md-right"></dt>{{--{{ '('.number_format($item->value,0,',','.').' x 12)' }}</dt> --}}
                                      <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                      @php
                                        $allowance += $item->value;
                                      @endphp
                                    @endif
                                @endif
                              @endforeach       
                              </dl>
                 
                        </div>
                        <div class="col-md-6">
                            <h4>Deduction</h4>
                            @php
                                $deduction = 0;
                            @endphp
                            <hr>
                                <dl class="row">
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                  @if ($item->variable->type == 2)
                                      <dt class="col-sm-4">{{ $item->variable->name }}</dt>
                                      @if ($item->variable->counter == 1)
                                        <dd class="col-sm-4 text-md-right">{{ '('.number_format($item->value,0,',','.').' x '.$days.')' }}</dt>
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value * $days,0,',','.') }}</dd>
                                        @php
                                            $deduction += $item->value * $days;
                                        @endphp
                                      @else
                                        <dd class="col-sm-4 text-md-right"></dt>{{--{{ '('.number_format($item->value,0,',','.').' x 12)' }}</dt> --}}
                                        <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                        @php
                                          $deduction += $item->value;
                                        @endphp
                                    @endif                             
                                  @endif
                                @endforeach 
                                @foreach (App\Models\Payroll\PayrollEmployeeVariables::where('payroll_employee_id',$model->id)->get() as $item)
                                @if ($item->variable->type == 4)
                                    <dt class="col-sm-4">{{ $item->variable->name }}</dt>
                                    @if ($item->variable->counter == 1)
                                      <dd class="col-sm-4 text-md-right">{{ '('.number_format($item->value,0,',','.').' x '.$days.')' }}</dt>
                                      <dd class="col-sm-4 text-md-right">{{ number_format($item->value * $days,0,',','.') }}</dd>
                                      @php
                                          $allowance += $item->value;
                                      @endphp
                                    @else
                                       <dd class="col-sm-4 text-md-right"></dt>{{--{{ '('.number_format($item->value,0,',','.').' x 12)' }}</dt> --}}
                                      <dd class="col-sm-4 text-md-right">{{ number_format($item->value,0,',','.') }}</dd>
                                      @php
                                        $allowance += $item->value;
                                      @endphp
                                    @endif
                                @endif
                              @endforeach   
                                </dl>   
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <dl class="row">
                          <dt class="col-sm-4">Total Allowance</dt>
                          <dd class="col-sm-8 text-md-right">{{ number_format($allowance+$model->basic_salary,0,',','.') }}</dd>
                      </dl> 
                      </div>
                      <div class="col-md-6">
                        <dl class="row">
                          <dt class="col-sm-4">Total Deduction</dt>
                          <dd class="col-sm-8 text-md-right">{{ number_format($deduction,0,',','.') }}</dd>
                      </dl>  
                      </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-sm-4">Pajak</dt>
                            <dd class="col-sm-8 text-md-right">{{ number_format($model->pajak,0,',','.') }}</dd>
                            @if ($model->employee->npwp)
                                @php
                                    $thp = $model->basic_salary + $allowance - $deduction - $model->pajak;
                                @endphp 
                            @else
                                @php
                                    $thp = $model->basic_salary + $allowance - $deduction - $model->pajak - round($model->tax_penalty);
                                @endphp 
                              <dt class="col-sm-4">Tax Penalty</dt>
                              <dd class="col-sm-8 text-md-right">{{ number_format(round($model->tax_penalty),0,',','.') }}</dd>                                
                            @endif

                            <dt class="col-sm-4">Take Home Pay</dt>
                            <dd class="col-sm-8 text-md-right">{{ number_format(($thp),0,',','.') }}</dd>
                        </dl>  
                    <hr>
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
