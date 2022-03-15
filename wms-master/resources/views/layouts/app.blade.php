<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}{{ isset($title) ? ' | ' . $title : '' }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">

    <style>
        /* .text-sm .select2-search__field {
            line-height: 27px !important;
        }

        .text-sm .select2-selection__rendered {
            line-height: 25px !important;
        } */

        /* .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        } */

        table.dataTable.dtr-inline.collapsed.table-sm>tbody>tr>td:first-child:before,
        table.dataTable.dtr-inline.collapsed.table-sm>tbody>tr>th:first-child:before {
            top: 2px;
            margin-top: .3rem;
        }

        .table {
            background-color: #fff;
        }

        .table tbody tr.active {
            background-color: rgba(0,0,0,.05);
        }

        div.dt-buttons {
            float: right;
        }
        .text-sm .btn-xs {
            font-size: .75rem!important;
        }

        .text-sm .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            font-size: 1rem !important;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }
    </style>
</head>

<body class="hold-transition layout-fixed text-sm {{ isset($collapse) ? 'sidebar-collapse' : '' }}">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/logo/emblem.png') }}" alt="MBPSLogo" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light text-sm">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('adminlte/dist/img/avatar5.png') }}"
                            class="user-image img-circle elevation-2" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-info">
                            <img src="{{ asset('adminlte/dist/img/avatar5.png') }}" class="img-circle elevation-2"
                                alt="User Image">

                            <p>
                                {{ Auth::user()->name }}
                                <small>
                                    @php
                                    $roles = Auth::user()->roles;
                                    @endphp
                                    @foreach ($roles as $key => $role)
                                    {{ $role->display_name }}
                                    @if ($key < count($roles) - 1) , @endif @endforeach </small> </p> </li> <!-- Menu
                                        Footer-->
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right"
                                onclick="event.preventDefault(); document.getElementById('logout').submit();">{{ __('Logout') }}</a>
                            <form method="POST" id="logout" action="{{ route('logout') }}">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-info elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('images/logo/emblem.png') }}" alt="AdminLTE Logo" class="brand-image">
                <span class="brand-text font-weight-light">WMS</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    @php
                    $segments = Request::segments();
                    $url = '/' . implode('/', $segments);
                    @endphp
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                        role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="/home" class="nav-link @if ($segments[0] == 'home') active @endif">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        @php
                        $role_ids = [];
                        foreach (Auth::user()->roles as $role) {
                            $role_ids[] = $role->id;
                        };
                        // $menus = App\Models\Menu::query()
                        //         ->whereNull('menu_id')
                        //         ->whereIn('id', function ($query) use ($role_ids) {
                        //             $query->select('menu_id')->from('menu_role')
                        //         ->whereIn('role_id', $role_ids);
                        //     })->get();
                        $menus = session('menus', []);
                        @endphp
                        @foreach ($menus as $menu)
                            @if (count($menu->menus) === 0)
                                <li class="nav-item">
                                    <a href="{{ $menu->url }}" class="nav-link @if ($segments[0] == $menu->name || $url == $menu->url) active @endif">
                                        <i class="nav-icon {{ $menu->icon }}"></i>
                                        <p>
                                            {{ $menu->display_name }}
                                        </p>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item @if ($segments[0] == $menu->name) menu-open @endif">
                                    <a href="#" class="nav-link @if ($segments[0] == $menu->name) active @endif">
                                        <i class="nav-icon {{ $menu->icon }}"></i>
                                        <p>
                                            {{ $menu->display_name }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @foreach ($menu->menus as $child)
                                        <li class="nav-item">
                                            <a href="{{ $child->url }}"
                                                class="nav-link @if ($url == $child->url) active @endif">
                                                <i class="nav-icon {{ $child->icon }}"></i>
                                                <p>
                                                    {{ $child->display_name }}
                                                </p>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                        {{-- @foreach ($menus as $menu)
                        @php
                        $childs = App\Models\Menu::query()
                                ->where('menu_id', '=', $menu->id)
                                ->whereIn('id', function ($query) use ($role_ids) {
                                $query->select('menu_id')->from('menu_role')
                                ->whereIn('role_id', $role_ids);
                            })->get();
                        @endphp
                        @if (count($childs) === 0)
                        <li class="nav-item">
                            <a href="{{ $menu->url }}" class="nav-link @if ($segments[0] == $menu->name || $url == $menu->url) active @endif">
                                <i class="nav-icon {{ $menu->icon }}"></i>
                                <p>
                                    {{ $menu->display_name }}
                                </p>
                            </a>
                        </li>
                        @else
                        <li class="nav-item @if ($segments[0] == $menu->name) menu-open @endif">
                            <a href="#" class="nav-link @if ($segments[0] == $menu->name) active @endif">
                                <i class="nav-icon {{ $menu->icon }}"></i>
                                <p>
                                    {{ $menu->display_name }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($childs as $child)
                                <li class="nav-item">
                                    <a href="{{ $child->url }}"
                                        class="nav-link @if ($url == $child->url) active @endif">
                                        <i class="nav-icon {{ $child->icon }}"></i>
                                        <p>
                                            {{ $child->display_name }}
                                        </p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        @endforeach --}}
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="padding-bottom: 1rem">
            @yield('content-header')

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>

            <!-- modal-dialog -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modal-title">Extra Large Modal</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="modal-btn-save">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal-dialog -->

            @yield('modal')

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="http://www.mitracomm.com/mbps/"
                    target="_blank">Mitracomm</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 0.0.1
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script>
        const loginUrl = "{{ route('login') }}";
    </script>
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    @stack('js')
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Fungsi formatRupiah
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";
        }

        $(function () {
            $(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
                if (event.key == "Enter") {
                    event.preventDefault();
                }
            });

            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('.number').keyup((e) => {
                $(e.target).val(formatRupiah($(e.target).val()));
                if ($(e.target).data('target')) {
                    $("#" + $(e.target).data('target')).val($(e.target).val().replaceAll('.', ''));
                }
            });
        });
    </script>
    @if (session('alert.success'))
    <script>
        $(document).ready(function(){
            Swal.fire({
                icon: 'success',
                title: '{{ session('alert.success') }}',
                timer: 2000,
                confirmButtonColor: '#3085d6',
            });
        });
    </script>
    @endif
    @if (session('alert.failed'))
    <script>
        $(document).ready(function(){
            Swal.fire({
                icon: 'error',
                title: '{{ session('alert.failed') }}',
                confirmButtonColor: '#3085d6',
            });
        });
    </script>
    @endif
    @stack('scripts')
</body>

</html>
