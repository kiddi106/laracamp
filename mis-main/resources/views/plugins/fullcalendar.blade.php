@push('css')
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset('/admin/plugins/fullcalendar/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/fullcalendar-daygrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/fullcalendar-timegrid/main.min.css') }}">
<link rel="stylesheet" href="{{ asset('/admin/plugins/fullcalendar-bootstrap/main.min.css') }}">
@endpush

@push('js')
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/fullcalendar/main.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/fullcalendar-daygrid/main.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/fullcalendar-timegrid/main.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/fullcalendar-interaction/main.min.js') }}"></script>
<script src="{{ asset('/admin/plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
@endpush
