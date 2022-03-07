
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mitracomm Ekasarana') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/admin/dist/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
<style>
    .datepicker[readonly] {
        background-color: #fff;
        opacity: unset;
    }
</style>
</head>

<body class="hold-transition" style="background: #e9ecef">

    @include('plugins.daterangepicker')

<div class="container">
    <div class="row justify-content-center">
            <div class="col-md-12">
            <br>
            <center>
                <img src="{{ asset('/img/emblem.png') }}" alt="MBPS Logo" width="10%" class=""> <b style="font-size: 40pt;vertical-align: middle;">MBPS</b>
            </center>
            <br>
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-8">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            <div class="input-group-append">
                                              <span class="input-group-text">@mitracomm.com</span>
                                            </div>
                                        </div>
                                        <small>Mitracomm Email</small>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
        
                                    <div class="col-md-8">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Password Confirmation') }}</label>

                                    <div class="col-md-8">
                                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="display_name" class="col-md-4 col-form-label text-md-right">Department</label>
                                    <div class="col-md-8">
                                        <select name="department_code" id="department_code" class="form-control" required>
                                            @php
                                                $departments = \App\Models\Auth\Department::all();
                                            @endphp
                                            <option value=""></option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->code }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="role_id" class="col-md-4 col-form-label text-md-right">Role</label>
                                    <div class="col-md-8">
                                        <select name="role_id" id="role_id" class="form-control" required></select>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="parent_uuid" class="col-md-4 col-form-label text-md-right">Direct Leader</label>
                                    <div class="col-md-8">
                                        <select name="parent_uuid" id="parent_uuid" class="form-control"></select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="empl_id" class="col-md-4 col-form-label text-md-right">Employee ID</label>

                                    <div class="col-md-8">
                                        <input id="empl_id" type="text" class="form-control @error('empl_id') is-invalid @enderror" name="empl_id" value="{{ old('empl_id') }}">

                                        @error('empl_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="join_date" class="col-md-4 col-form-label text-md-right">{{ __('Join Date') }}</label>

                                    <div class="col-md-8">
                                        <input type="text" id="_join_date" class="form-control datepicker @error('join_date') is-invalid @enderror" value="{{ sqlindo_date(old('join_date')) }}" readonly>
                                        <input type="hidden" name="join_date" value="{{ old('join_date') }}">

                                        @error('join_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="ext_no" class="col-md-4 col-form-label text-md-right">Ext. No</label>

                                    <div class="col-md-8">
                                        <input id="ext_no" type="text" class="form-control @error('ext_no') is-invalid @enderror" name="ext_no" value="{{ old('ext_no') }}">

                                        @error('ext_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile_no" class="col-md-4 col-form-label text-md-right">Mobile No.</label>

                                    <div class="col-md-8">
                                        <input id="mobile_no" type="text" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" value="{{ old('mobile_no') }}">

                                        @error('mobile_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="pob" class="col-md-4 col-form-label text-md-right">{{ __('Place of Birth') }}</label>

                                    <div class="col-md-8">
                                        <input id="pob" type="text" class="form-control @error('pob') is-invalid @enderror" name="pob" value="{{ old('pob') }}" required>

                                        @error('pob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                                    <div class="col-md-8">
                                        <input type="text" id="_dob" class="form-control datepicker @error('dob') is-invalid @enderror" value="{{ sqlindo_date(old('dob')) }}" required readonly>
                                        <input type="hidden" name="dob" value="{{ old('dob') }}">

                                        @error('dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <input id="loc" type="hidden" readonly class="form-control loc @error('loc') is-invalid @enderror" name="loc" value="{{ old('loc') }}" required>
        
                                        @error('loc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-right">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
    <!-- jQuery -->
    <script src="{{ asset('/js/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/admin/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script>
        $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
        $('[name^="' + $(this).attr('id').replace('_', '') + '"]').val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('#department_code').change( () => {
        Swal.showLoading();
        var department_code = $('#department_code').val();
        $('#role_id').empty();
        $.ajax({
            url : '{{ route("roles") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                department_code
            },
            success: (data) => {
                var str_data = '<option value="">--Please Select Role--</option>';
                str_data += data.map((role) => {
                    return '<option value="'+role.id+'">'+role.display_name+'</option>';
                });
                $('#role_id').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });
    $('#role_id').change( () => {
        Swal.showLoading();
        var role_id = $('#role_id').val();
        $('#parent_uuid').empty();
        $.ajax({
            url : '{{ route("parent_employee") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType : 'json',
            data: {
                role_id
            },
            success: (data) => {
                var str_data = '<option value=""></option>';
                str_data += data.map((empl) => {
                    return '<option value="' + empl.uuid + '">' + empl.name + ' (' + empl.email +')</option>';
                });
                $('#parent_uuid').append(str_data);
                Swal.close();
            },
            error: (xhr) => {
                console.log(xhr);
                Swal.close();
            }
        });
    });

    $(document).ready( function () {
        getLocation()
    });

        function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            alert("Geolocation is not supported by this browser.")
        }
        }
        function showPosition(position) {
            var loc = position.coords.latitude+', '+position.coords.longitude
            $.ajax({
                url: "{{ route('getLocation') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {loc: loc},
                dataType: 'html',
                success: function (response) 
                {
                    $('.loc').val(response)
                }
            });
        }
</script>
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
                    type: 'danger',
                    title: '{{ session('alert.failed') }}',
                    timer: 2000,
                })
            })
        </script>
    @endif
</html>