<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PriorityIcon extends Component
{
    public $priority;

    public function __construct($priority)
    {
        $this->priority = $priority;
    }

    public function render()
    {
        return view('components.priority-icon');
    }
}
