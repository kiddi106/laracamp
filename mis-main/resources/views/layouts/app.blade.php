<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mitracomm Ekasarana') }}</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/admin/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <!-- Data Grid -->
    <link rel="stylesheet" href="{{ asset('/handsontable/handsontable.full.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" /> --}}

    @stack('css')
    @stack('styles')
</head>
<body class="hold-transition layout-top-nav text-sm">
<div class="wrapper">
    @include('layouts._navbar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content-header')

        <div class="content">
            @include('layouts._modal')
            @yield('content')
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('layouts._footer')
</div>
<!-- REQUIRED SCRIPTS -->
<script>
    var loginUrl = "{{ route('login') }}"
</script>
<!-- jQuery -->
<script src=" {{ asset('/admin/plugins/jquery/jquery.min.js') }}"></script>
<script src=" {{ asset('/js/app.js') }}"></script>
<!-- Bootstrap 4 -->
<script src=" {{ asset('/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src=" {{ asset('/admin/dist/js/adminlte.min.js') }}"></script>
<!-- datatables -->
<script src=" {{ asset('/admin/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src=" {{ asset('/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src=" {{ asset('/js/sweetalert2.all.min.js') }}"></script>
<!-- Data Grid -->
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script> --}}
<script src=" {{ asset('/handsontable/handsontable.full.min.js') }}"></script>


@stack('js')

@stack('scripts')
    @if (session('alert.success'))
        <script>
            $(document).ready(function(){
                Swal.fire({
                    type: 'success',
                    title: '{{ session('alert.success') }}',
                    timer: 2000,
                })
            })
        </script>
    @endif
    @if (session('alert.failed'))
        <script>
            $(document).ready(function(){
                Swal.fire({
                    type: 'error',
                    title: '{{ session('alert.failed') }}',
                    timer: 2000,
                })
            })
        </script>
    @endif
</body>
</html>
