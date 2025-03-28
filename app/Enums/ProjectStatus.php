<?php

namespace App\Enums;

enum ProjectStatus: int
{
    case PRE_CONTRACT = 0;
    case RUNNING = 1;
    case PENDING = 2;
    case SUCCESS = 3;
    case CANCELED = 4;

    // Hàm hỗ trợ lấy tên của status
    public function label(): string
    {
        return match ($this) {
            self::PRE_CONTRACT => 'Pre-contract',
            self::RUNNING => 'Running',
            self::PENDING => 'Pending',
            self::SUCCESS => 'Success',
            self::CANCELED => 'Canceled',
        };
    }
}