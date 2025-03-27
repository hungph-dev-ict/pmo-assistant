<?php

use App\Http\Controllers\TenantController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PmController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorklogController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TaskExportController;

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::middleware('auth')->group(function () {
    // Route::get('/', function () {
    //     return view('dashboard');
    // });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('password.custom-update');

    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-worklog', [WorklogController::class, 'viewMyWorklog'])->name('my-worklog');
    Route::get('/api/my-worklog', [WorklogController::class, 'getMyWorklogs']);
    Route::put('/api/worklog/{worklog_id}/update', [WorklogController::class, 'updateWorklog']);
    Route::delete('/api/worklog/{worklog_id}/destroy', [WorklogController::class, 'softDeleteWorklog']);
    Route::get('/api/{project_id}/task/{task_id}/worklog', [WorklogController::class, 'get']);
    Route::post('/api/{project_id}/tasks/{task_id}/worklog', [WorklogController::class, 'store']);

    Route::get('/leave-request', [LeaveRequestController::class, 'viewLeaveRequest'])->name('leave-request');
    Route::get('/api/leave-request', [LeaveRequestController::class, 'getMyLeaveRequests']);
    Route::get('/api/leave-request/types', [LeaveRequestController::class, 'getLeaveRequestType']);
    Route::get('/api/leave-request/approvers', [LeaveRequestController::class, 'getLeaveRequestApprover']);
    Route::put('/api/leave-request/{leave_id}/update', [LeaveRequestController::class, 'updateLeaveRequest']);
    Route::put('/api/leave-request/update-status', [LeaveRequestController::class, 'updateLeaveStatus']);
    Route::delete('/api/leave-request/{leave_id}/destroy', [LeaveRequestController::class, 'softDeleteLeaveRequest']);
    Route::post('/api/leave-request/store', [LeaveRequestController::class, 'store']);
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::resource('/tenants', TenantController::class);
    // Route để khôi phục tenant
    Route::post('tenants/{id}/restore', [TenantController::class, 'restore'])->name('tenants.restore');
    Route::resource('/plans', PlanController::class);
});

Route::group(['middleware' => ['auth', 'role:client']], function () {
    // Route::resource('users', UserController::class);
    Route::get('tenant/{tenant_id}/users', [UserController::class, 'getTenantUsers'])->name('client.users.list');
    Route::get('tenant/{tenant_id}/users/create', [UserController::class, 'createTenantUser'])->name('client.users.create');
    Route::get('tenant/{tenant_id}/users/{user_id}/edit', [UserController::class, 'editTenantUser'])->name('client.users.edit');
    Route::put('tenant/{tenant_id}/users/{user_id}/update', [UserController::class, 'updateTenantUser'])->name('client.users.update');
    Route::post('tenant/{tenant_id}/users/store', [UserController::class, 'storeTenantUser'])->name('client.users.store');
    Route::post('tenant/{tenant_id}/users/store/form', [UserController::class, 'storeByForm'])->name('client.users.store.form');
    Route::get('tenant/{tenant_id}/worklogs/management', [WorklogController::class, 'viewTenantWorklogs'])->name('client.worklogs.management');
    Route::delete('tenant/{tenant_id}/users/{user_id}', [UserController::class, 'destroy'])->name('client.users.destroy');
    Route::post('tenant/{tenant_id}/users/{user_id}/restore', [UserController::class, 'restore'])->name('client.users.restore');
});

Route::group(['middleware' => ['auth', 'role:admin|client|pm']], function () {
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');

    Route::get('/api/pm/{project_id}/tasks', [PmController::class, 'listTasks']);
    Route::get('/api/pm/{project_id}/list', [PmController::class, 'listTasksByFilter']);
    Route::get('/api/pm/{project_id}/epics', [PmController::class, 'listEpics']);
    Route::post('/api/pm/{project_id}/tasks/store', [PmController::class, 'storeTask']);
    Route::put('/api/pm/{project_id}/tasks/{task_id}/update', [PmController::class, 'updateTask']);
    Route::delete('/api/pm/{project_id}/tasks/{task_id}/destroy', [PmController::class, 'softDeleteTask']);
    Route::get('/api/tenant-worklog', [WorklogController::class, 'getTenantWorklogs']);
    Route::get('/api/tenant-leave-request', [WorklogController::class, 'getTenantLeaveRequests']);
    Route::get('/api/project/{project_id}/worklog', [WorklogController::class, 'getProjectWorklogs']);    
    Route::get('/api/leave-request-management', [LeaveRequestController::class, 'getLeaveRequestsManagement']);

    Route::prefix('pm/{project_id}')->group(function () {
        Route::get('/task', [PmController::class, 'listTasks'])->name('pm.task');
        Route::get('/task/{task_id}', [TaskController::class, 'show'])->name('pm.task.show');
        Route::get('/member', [PmController::class, 'listMembers'])->name('pm.member');
        Route::post('/member/update', [PmController::class, 'updateMembers'])->name('pm.member.update');
        Route::get('/worklogs/management', [WorklogController::class, 'viewProjectWorklogs'])->name('pm.worklogs.management');
        Route::get('/chart', [PmController::class, 'viewChart'])->name('pm.chart');
        Route::get('/components', [PmController::class, 'viewComponents'])->name('pm.components');
        Route::post('/components', [PmController::class, 'createComponents'])->name('pm.components.create');
        Route::delete('/components/{id}', [PmController::class, 'destroy'])->name('pm.components.delete');
        Route::post('/components/{id}/restore', [PmController::class, 'restore'])->name('pm.components.restore');
    });
});

Route::group(['middleware' => ['auth', 'role:client|pm|staff']], function () {
    Route::get('/api/staff/{project_id}/tasks', [StaffController::class, 'listTasks']);
    Route::get('/api/staff/{project_id}/list', [StaffController::class, 'listTasksByFilter']);
    Route::get('/api/project/{project_id}/worklog', [WorklogController::class, 'getProjectWorklogs']);
    Route::get('/api/project/{project_id}/users-without-worklogs', [WorklogController::class, 'getWorlogAuditInWeek']);
    Route::get('/api/{project_id}/getAllMembers', [ProjectController::class, 'getAllMembers']);
    Route::get('/api/project-metrics/{project_id}', [ProjectController::class, 'getProjectMetrics']);
    Route::get('/api/getAllStatuses', [TaskController::class, 'getStatuses']);
    Route::get('/api/getAllPriorities', [TaskController::class, 'getPriorities']);
    Route::put('/api/staff/{project_id}/tasks/{task_id}/update', [StaffController::class, 'updateTask']);

    Route::get('/export-tasks', [TaskExportController::class, 'export'])->name('tasks.export');
    Route::put('/api/tasks/{task_id}/update', [TaskController::class, 'updateTask']);

    Route::prefix('staff/{project_id}')->group(function () {
        Route::get('/task', [StaffController::class, 'listTasks'])->name('staff.task');
        Route::get('/task/{task_id}', [TaskController::class, 'show'])->name('staff.task.show');
        Route::get('/task/{task_id}/edit', [StaffController::class, 'listTasks'])->name('task.edit');
        Route::get('/task/{task_id}/destroy', [StaffController::class, 'listTasks'])->name('task.destroy');
        Route::get('/task', [StaffController::class, 'listTasks'])->name('staff.task');
        Route::get('/member', [StaffController::class, 'listMembers'])->name('staff.member');
        Route::get('/chart', [StaffController::class, 'viewChart'])->name('staff.chart');
    });
});

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        return "Database connection is working!";
    } catch (\Exception $e) {
        return "Database connection error: " . $e->getMessage();
    }
});

Route::get('locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'jp', 'vi'])) {
        session(['lang' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
});

Route::get('/{project_id}/task/{task_id}', function ($project_id, $task_id) {
    return redirect()->route('redirect.task', ['project_id' => $project_id, 'task_id' => $task_id]);
})->middleware('auth')->name('task.redirect');

Route::get('/redirect/{project_id}/task/{task_id}', function ($task_id, $project_id) {
    return app(\App\Http\Middleware\RedirectTaskRoute::class)->handle(request(), function () {});
})->middleware('auth')->name('redirect.task');

require __DIR__ . '/auth.php';
