<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>PMO-A | @yield('page_title', config('app.name', 'PMO Assistant'))</title>
@vite(['resources/js/plugins/fontawesome-free/css/all.min.css'])
<link rel="stylesheet" href="{{ asset('build/css/plugins/flag-icon-css.css') }}">
<!-- Font Awesome Icons -->
{{-- <script src="https://code.jscharting.com/latest/jscharting.js"></script> --}}
<!-- Inline CSS -->
@yield('inline_css')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css"> --}}
@vite(['resources/css/app.css'])