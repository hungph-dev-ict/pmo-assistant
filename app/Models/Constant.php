<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'key', 'value1', 'value2', 'value3', 'value4', 'value5'];

    public static function getPriorityList()
    {
        return self::where('group', 'task_priority')
            ->orderBy('key', 'desc')
            ->pluck('value1', 'key');
    }
}
