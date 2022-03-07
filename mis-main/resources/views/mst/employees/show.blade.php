<form method="POST" action="{{ route('register') }}">
    @csrf
    <br>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->name }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->email }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="display_name" class="col-md-4 col-form-label text-md-right">Department</label>
                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $department->name }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="role_id" class="col-md-4 col-form-label text-md-right">Role</label>
                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->roles[0]->display_name }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="parent_uuid" class="col-md-4 col-form-label text-md-right">Direct Leader</label>
                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $directLeader->name }}</label>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group row">
                <label for="empl_id" class="col-md-4 col-form-label text-md-right">Employee ID</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->empl_id }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="join_date" class="col-md-4 col-form-label text-md-right">{{ __('Join Date') }}</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">
                    @php
                        $date = date_create($employee->join_date);
                        $tahun = date_format($date,"Y");
                        $bulan = date_format($date,"m");
                        $hari = date_format($date,"d")+1;

                        echo gmdate("d F Y",mktime(0,0,0,$bulan,$hari,$tahun));
                    @endphp
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <label for="ext_no" class="col-md-4 col-form-label text-md-right">Ext. No</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->ext_no }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile_no" class="col-md-4 col-form-label text-md-right">Mobile No.</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->mobile_no }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="pob" class="col-md-4 col-form-label text-md-right">{{ __('Place of Birth') }}</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">{{ $employee->pob }}</label>
                </div>
            </div>

            <div class="form-group row">
                <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                <div class="col-md-6">
                    <label class="col-form-label" style="font-weight: normal">
                    @php
                        $date = date_create($employee->dob);
                        $tahun = date_format($date,"Y");
                        $bulan = date_format($date,"m");
                        $hari = date_format($date,"d")+1;

                        echo gmdate("d F Y",mktime(0,0,0,$bulan,$hari,$tahun));
                    @endphp
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>