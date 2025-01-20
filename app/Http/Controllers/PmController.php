<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PmController extends Controller
{
    public function listTasks()
    {
        return view('pm.task');
    }

    
    public function listMembers()
    {
        return view('pm.member');
    }

    
    public function viewChart()
    {
        return view('pm.chart');
    }
}
