@push('css')
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('/admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('scripts')
    <script>
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        }) 
    </script>
@endpush
