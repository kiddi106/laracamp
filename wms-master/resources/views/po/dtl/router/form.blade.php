<form class="form-horizontal"
    action="{{ $model->exists ? route('po.dtl.router.update', $model->id) : route('po.dtl.router.store') }}" method="POST">
    {{ csrf_field() }}
    @if ($model->exists)
    <input type="hidden" name="_method" value="PUT">
    @endif

    <input type="hidden" name="po_dtl_id" value="{{ $model->exists ? $model->po_dtl_id : $po_dtl_id }}">

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="esn" class="col-sm-4 col-form-label">ESN</label>
                <div class="col-sm-8">
                    <input type="text" name="esn" id="esn" class="form-control" placeholder="ESN" @if ($model->exists) value="{{ $model->esn }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="ssid" class="col-sm-4 col-form-label">SSID</label>
                <div class="col-sm-8">
                    <input type="text" name="ssid" id="ssid" class="form-control" placeholder="SSID" @if ($model->exists) value="{{ $model->ssid }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="password_router" class="col-sm-4 col-form-label">PASSWORD ROUTER</label>
                <div class="col-sm-8">
                    <input type="text" name="password_router" id="password_router" class="form-control" placeholder="PASSWORD ROUTER" @if ($model->exists) value="{{ $model->password_router }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="guest_ssid" class="col-sm-4 col-form-label">GUEST SSID</label>
                <div class="col-sm-8">
                    <input type="text" name="guest_ssid" id="guest_ssid" class="form-control" placeholder="GUEST SSID" @if ($model->exists) value="{{ $model->guest_ssid }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="password_guest" class="col-sm-4 col-form-label">PASSWORD GUEST</label>
                <div class="col-sm-8">
                    <input type="text" name="password_guest" id="password_guest" class="form-control" placeholder="PASSWORD GUEST" @if ($model->exists) value="{{ $model->password_guest }}" @endif>
                </div>
            </div>
            <div class="form-group row">
                <label for="password_admin" class="col-sm-4 col-form-label">PASSWORD ADMIN WEB</label>
                <div class="col-sm-8">
                    <input type="text" name="password_admin" id="password_admin" class="form-control" placeholder="PASSWORD ADMIN WEB" @if ($model->exists) value="{{ $model->password_admin }}" @endif>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="imei" class="col-sm-4 col-form-label">IMEI</label>
                <div class="col-sm-8">
                    <input type="text" name="imei" id="imei" class="form-control" placeholder="IMEI" @if ($model->exists) value="{{ $model->imei }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="device_model" class="col-sm-4 col-form-label">DEVICE MODEL</label>
                <div class="col-sm-8">
                    <input type="text" name="device_model" id="device_model" class="form-control" placeholder="DEVICE MODEL" @if ($model->exists) value="{{ $model->device_model }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="device_type" class="col-sm-4 col-form-label">DEVICE TYPE</label>
                <div class="col-sm-8">
                    <input type="text" name="device_type" id="device_type" class="form-control" placeholder="DEVICE TYPE" @if ($model->exists) value="{{ $model->device_type }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="color" class="col-sm-4 col-form-label">Color</label>
                <div class="col-sm-8">
                    <input type="text" name="color" id="color" class="form-control" placeholder="Color" @if ($model->exists) value="{{ $model->color }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="condition" class="col-sm-4 col-form-label">Condition</label>
                <div class="col-sm-8">
                    <select name="condition" id="condition" class="form-control">
                        <option value="GOOD" @if (!$model->exists) selected @elseif ($model->condition == 'GOOD') selected @endif>GOOD</option>
                        <option value="BAD" @if ($model->exists && $model->condition == 'BAD') selected @endif>BAD</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

{{-- condition
location
notes, --}}
</form>
