@extends('layouts.app')

@section('page_title')
    Task List
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active"><a href="{{ route('projects.index') }}">Tasks</a></li>
@endsection

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
@endsection

@section('content')
<div id="task-app">
    <task-container project-id="{{ $project_id }}"></task-container>
</div>
@endsection

@section('inline_js')
    <!-- Select2 -->
    <script src="{{ asset('build/js/plugins/select2-full-js.js') }}"></script>
    <!-- Moment.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    {{-- <script src="{{ asset('build/js/plugins/moment-js.js') }}"></script> --}}
    <!-- Tempus Dominus JS -->
    <script src="{{ asset('build/js/plugins/tempusdominus-bootstrap-4-js.js') }}"></script>
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: "Choose an assignee", // Placeholder hiển thị khi không có lựa chọn
                allowClear: true // Bật tính năng xóa lựa chọn
            })
            $('#planStartDatePicker').datetimepicker({
                format: 'YYYY-MM-DD', // Định dạng ngày
                useCurrent: false,
            });
            // Khởi tạo datetimepicker cho reservationdate
            $('#planEndDatePicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false,
            });
            $('#actualStartDatePicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#actualEndDatePicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            // Ràng buộc khi thay đổi Start Date
            $('#planStartDatePicker').on('change.datetimepicker', function(e) {
                // Cập nhật minDate cho End Date khi Start Date thay đổi
                $('#planStartDatePicker').datetimepicker('minDate', e.date);
            });
            // Ràng buộc khi thay đổi End Date
            $('#actualStartDatePicker').on('change.datetimepicker', function(e) {
                // Cập nhật maxDate cho Start Date khi End Date thay đổi
                $('#actualStartDatePicker').datetimepicker('maxDate', e.date);
            });
            $('#actualEndDatePicker').on('change.datetimepicker', function(e) {
                // Cập nhật minDate cho End Date khi Start Date thay đổi
                $('#actualEndDatePicker').datetimepicker('minDate', e.date);
            });

        })
    </script>
@endsection
