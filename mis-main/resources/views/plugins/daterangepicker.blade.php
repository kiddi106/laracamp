@push('css')
    <link rel="stylesheet" href="{{ asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('styles')
<style>
    .datepicker[readonly] {
        background-color: #fff;
        opacity: unset;
    }
</style>
@endpush

@push('js')
    <script src="{{ asset('/admin/plugins/moment/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('scripts')
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
</script>
@endpush