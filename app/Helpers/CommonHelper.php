<?php

if (!function_exists('getStatusClass')) {
    function getStatusClass($status) {
        switch ($status) {
            case 0: // Not Started
                return "badge badge-secondary"; // Màu xám
            case 1: // In Progress
                return "badge badge-primary"; // Màu xanh dương
            case 2: // Resolved
                return "badge badge-success"; // Màu xanh lá
            case 3: // Feedback
                return "badge badge-warning"; // Màu vàng
            case 4: // Done
                return "badge badge-dark"; // Màu đen hoặc tím đậm (đã sửa)
            case 5: // Reopen
                return "badge badge-secondary"; // Màu xám
            case 6: // Pending
                return "badge badge-warning"; // Màu cam
            case 7: // Canceled
                return "badge badge-danger"; // Màu đỏ
            default:
                return "badge badge-light"; // Màu nhạt cho trạng thái không xác định
        }
    }
}
