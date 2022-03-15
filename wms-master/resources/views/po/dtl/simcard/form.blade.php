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
                <label for="serial_no" class="col-sm-4 col-form-label">SN</label>
                <div class="col-sm-8">
                    <input type="text" name="serial_no" id="serial_no" class="form-control" placeholder="ESN" @if ($model->exists) value="{{ $model->serial_no }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="msisdn" class="col-sm-4 col-form-label">MSISDN</label>
                <div class="col-sm-8">
                    <input type="text" name="msisdn" id="msisdn" class="form-control" placeholder="MSISDN" @if ($model->exists) value="{{ $model->msisdn }}" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="item_code" class="col-sm-4 col-form-label">ITEM CODE</label>
                <div class="col-sm-8">
                    <input type="text" name="item_code" id="password_router" class="form-control" placeholder="ITEM CODE" @if ($model->exists) value="{{ $model->item_code }}" @endif>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="exp_at" class="col-sm-4 col-form-label">Expired</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control" id="exp_at" name="exp_at" placeholder="dd/mm/yyyy" required @if ($model->exists) value="{{ $model->exp_at }}" @endif>
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
