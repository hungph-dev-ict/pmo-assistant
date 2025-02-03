<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PmController extends Controller
{
    public function listTasks()
    {
        $users = [];

        if (Auth::user()->hasRole('client')) {
            $users = User::where('tenant_id', Auth::id())->get();
        } elseif (Auth::user()->hasRole('pm')) {
            $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        }

        return view('pm.task', compact('users'));
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
