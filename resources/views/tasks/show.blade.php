@extends('layouts.app')

@section('page_title')
    {{ $task->name }}
@endsection

@section('custom_meta')
    <meta property="og:title" content="Chi tiết công việc - {{ $task->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($task->description), 100) }}">
    <meta property="og:image" content="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Chi tiết công việc - {{ $task->name }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($task->description), 100) }}">
    <meta name="twitter:image" content="{{ Vite::asset('resources/images/adminlte/pmo-a_main.png') }}">
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">
        @if (auth()->user()->hasRole(['pm', 'client']))
            <a href="{{ route('pm.task', $task->project->id) }}">{{ $task->project->name }}</a>
        @else
            <a href="{{ route('staff.task', $task->project->id) }}">{{ $task->project->name }}</a>
        @endif
    </li>
    <li class="breadcrumb-item active">{{ $task->name }}</li>
@endsection

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
    @vite(['resources/js/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'])
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('content')
    <div data-vue-app="task-detail" data-task='@json($task)'
        data-list-assignee='@json($listAssignee)' data-user-role="{{ auth()->user()->getRoleNames() }}"></div>
@endsection

@section('inline_js')
    <!-- Select2 -->
    <script src="{{ asset('build/js/plugins/select2-full-js.js') }}"></script>
    <!-- Moment.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    {{-- <script src="{{ asset('build/js/plugins/moment-js.js') }}"></script> --}}
    <!-- Tempus Dominus JS -->
    <script src="{{ asset('build/js/plugins/tempusdominus-bootstrap-4-js.js') }}"></script>
    <!-- SweetAlert2 -->
    @vite(['resources/js/plugins/sweetalert2/sweetalert2.min.js'])
    @vite(['resources/js/plugins/toastr/toastr.min.js'])
@endsection

@section('custom_inline_js')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                placeholder: "Choose one", // Placeholder hiển thị khi không có lựa chọn
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
                format: 'YYYY-MM-DD',
                useCurrent: false,
            });
            $('#actualEndDatePicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false,
            });
        });
    </script>
@endsection
