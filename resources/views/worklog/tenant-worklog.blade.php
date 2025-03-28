@extends('layouts.app')

@section('page_title')
    Tenant Worklog
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Tenant Worklog</li>
@endsection

@section('inline_css')
    <link rel="stylesheet" href="{{ asset('build/css/plugins/tempusdominus-bootstrap-4-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-min-css.css') }}">
    <link rel="stylesheet" href="{{ asset('build/css/plugins/select2-bootstrap4-css.css') }}">
    @vite(['resources/js/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'])
    @vite(['resources/js/plugins/toastr/toastr.min.css'])
@endsection

@section('content')
    <div data-vue-app="worklog-management-container"></div>
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
