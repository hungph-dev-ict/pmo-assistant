<?php

namespace App\Enums;

enum TaskStatus: int
{
    case OPEN = 0;
    case IN_PROGRESS = 1;
    case RESOLVED = 2;
    case FEEDBACK = 3;
    case DONE = 4;
    case REOPEN = 5;
    case PENDING = 6;
    case CANCELED = 7;

    // Hàm hỗ trợ lấy tên của status
    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::RESOLVED => 'Resolved',
            self::FEEDBACK => 'Feedback',
            self::DONE => 'Done',
            self::REOPEN => 'Reopen',
            self::PENDING => 'Pending',
            self::CANCELED => 'Canceled',
        };
    }
}
