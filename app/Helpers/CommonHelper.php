<?php

if (!function_exists('getStatusClass')) {
    function getStatusClass($status) {
        switch ($status) {
            case 0:
                return "badge badge-secondary"; 
            case 1:
                return "badge badge-primary"; 
            case 2:
                return "badge badge-success"; 
            case 3:
                return "badge badge-warning"; 
            case 4:
                return "badge badge-dark"; 
            case 5:
                return "badge badge-secondary"; 
            default:
                return "badge badge-light"; 
        }
    }
}
