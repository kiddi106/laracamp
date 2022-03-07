@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        {{-- <div class="container"> --}}
        <div class="row mb-2">
            <div class="col-sm-6">
            	<h1 class="m-0 text-dark">Administration</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Administration</a></li>
					<li class="breadcrumb-item active">Letter</li>
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
                        <ul class="nav nav-tabs" id="letter" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $active = ($tab_id == 'alldoc') ? 'active' : '' }}" id="letter-alldoc-tab" href="{{ route('adm.letter.index', ['tab_id' => 'alldoc']) }}" role="tab" aria-controls="letter-alldoc" aria-selected="{{ $aria = ($tab_id == 'alldoc') ? 'true' : 'false' }}">All Document</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $active = ($tab_id == 'mydoc') ? 'active' : '' }}" id="letter-mydoc-tab" href="{{ route('adm.letter.index', ['tab_id' => 'mydoc']) }}" role="tab" aria-controls="letter-mydoc" aria-selected="{{ $aria = ($tab_id == 'mydoc') ? 'true' : 'false' }}">My Document</a>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="letterContent">
                            <div class="tab-pane fade show active" id="letter-{{ $tab_id }}" role="tabpanel" aria-labelledby="letter-{{ $tab_id }}-tab">
                                @if ($tab_id == 'alldoc')
                                    @include('adm.letter.alldoc')
                                @elseif ($tab_id == 'mydoc')
                                    @include('adm.letter.mydoc')
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
</div>
<!-- /.wrapper -->
@include('layouts/_modal')

@endsection
