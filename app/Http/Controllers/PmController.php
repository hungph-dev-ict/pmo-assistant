<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PmController extends Controller
{
    public function listTasks()
    {
        $loggedInUser = Auth::user();
        $users = [];
        if ($loggedInUser->hasRole('client')) {
            $users = User::where('tenant_head_acc_id', $loggedInUser->id)->get();
        } elseif ($loggedInUser->hasRole('pm')) {
            $users = User::where('tenant_head_acc_id', $loggedInUser->tenant_head_acc_id)->get();
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
