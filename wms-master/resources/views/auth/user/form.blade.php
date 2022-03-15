<form class="form-horizontal"
    action="{{ $model->exists ? route('auth.user.update', $model->id) : route('auth.user.store') }}" method="POST">
    {{ csrf_field() }}
    @if ($model->exists)
    <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" name="name" id="name" class="form-control" placeholder="Name" @if ($model->exists) value="{{ $model->name }}" @endif>
        </div>
    </div>

    <div class="form-group row">
        <label for="username" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" @if ($model->exists) value="{{ $model->username }}" @endif>
        </div>
    </div>

    @if (!$model->exists)
    <div class="form-group row">
        <label for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" id="password" placeholder="Password" class="form-control">
        </div>
    </div>
    @endif
</form>
