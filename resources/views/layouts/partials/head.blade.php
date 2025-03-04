<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

@yield('custom_meta') <!-- Đây là chỗ để chèn meta tùy chỉnh từ từng trang -->
<meta property="og:type" content="website">

<title>PMO-A | @yield('page_title', config('app.name', 'PMO Assistant'))</title>
@vite(['resources/js/plugins/fontawesome-free/css/all.min.css'])
@vite(['resources/js/plugins/flag-icon-css/css/flag-icon.min.css'])
<!-- Font Awesome Icons -->
<script src="https://code.jscharting.com/latest/jscharting.js"></script>
<!-- Inline CSS -->
@yield('inline_css')
<style>
    /* Tuỳ chỉnh theme cho Tippy */
    .tippy-box {
        font-size: 14px;
        /* Chữ to hơn */
        background-color: rgba(0, 0, 0, 0.9);
        /* Đậm hơn */
        color: white;
        /* Chữ trắng nổi bật */
        padding: 8px 12px;
        /* Tăng kích thước */
        border-radius: 6px;
        /* Bo góc */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        /* Đổ bóng */
    }

    .tippy-arrow {
        color: rgba(0, 0, 0, 0.9);
        /* Màu mũi tên */
    }
</style>
@vite(['resources/css/app.css'])
