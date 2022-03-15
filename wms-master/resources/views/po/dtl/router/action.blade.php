@if ($model->status_id === 1)
    <a href="{{ route('po.dtl.router.edit', ['id' => $model->id]) }}" class="btn btn-default btn-xs modal-show edit" title="Edit Router"><i class="fas fa-edit"></i></a>
@endif
