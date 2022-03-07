@extends('layouts.app')

@push('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- date picker -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <style>
        .dropdown-menu-date {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 10rem;
            padding: .5rem 0;
            margin: .125rem 0 0;
            /* font-size: 1rem; */
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: .25rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.175);
        }
    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
        <div class="row mb-2">
            <div class="col-sm-6">
            	<h1 class="m-0 text-dark"> General <small>Affair</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">General Affair</a></li>
					<li class="breadcrumb-item active">Vehicle</li>
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
            <div class="col-12">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">
                        <ul class="nav nav-tabs" id="tab-vehicle" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $active = ($tab_id == 'schedule') ? 'active' : '' }}" id="tab-vehicle-schedule-tab" href="{{ route('ga.vehicle.home', ['tab_id' => 'schedule']) }}" role="tab" aria-controls="tab-vehicle-schedule" aria-selected="{{ $aria = ($tab_id == 'schedule') ? 'true' : 'false' }}">Vehicle Schedule</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ $active = ($tab_id == 'waiting-list') ? 'active' : '' }}" id="vehicle-waitingList-tab" href="{{ route('ga.vehicle.home', ['tab_id' => 'waiting-list']) }}" role="tab" aria-controls="vehicle-waitingList" aria-selected="{{ $aria = ($tab_id == 'waiting-list') ? 'true' : 'false' }}">Vehicle Waiting List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $active = ($tab_id == 'admin') ? 'active' : '' }}" id="vehicle-admin-tab" href="{{ route('ga.vehicle.home', ['tab_id' => 'admin']) }}" role="tab" aria-controls="vehicle-admin" aria-selected="{{ $aria = ($tab_id == 'admin') ? 'true' : 'false' }}">Vehicle Admin</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="vehicleContent">
                            <div class="tab-pane fade show active" id="vehicle-{{ $tab_id }}" role="tabpanel" aria-labelledby="vehicle-{{ $tab_id }}-tab">
                                @if ($tab_id == 'schedule')
                                    @if ($role_driver[0]->name == 'Drv')
                                        @include('ga.vehicle.scheduleDriver')
                                    @else
                                        @include('ga.vehicle.schedule')
                                    @endif
                                @elseif ($tab_id == 'waiting-list')
                                    @include('ga.vehicle.waitingList')
                                @elseif ($tab_id == 'admin')
                                    @include('ga.vehicle.admin')
                                @else
                                    {{"Module not found"}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
		</div>
          <!-- /.row -->
        {{-- </div><!-- /.container-fluid --> --}}
    </div>
	<!-- /.content -->

@include('layouts._modal')

@push('js')
    <!-- date picker -->
    <script src="{{ asset('/admin/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/timepicker/js/bootstrap-timepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endpush
@endsection
