<?php

use App\Http\Controllers\TenantController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PmController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\WorklogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    //     Route::resource('projects', ProjectController::class);


    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    Route::post('tenant/{tenant_id}/users/store', [UserController::class, 'storeTenantUser'])->name('client.users.store');
    Route::post('tenant/{tenant_id}/users/store/form', [UserController::class, 'storeByForm'])->name('client.users.store.form');
    Route::get('tenant/{tenant_id}/worklogs/management', [WorklogController::class, 'viewTenantWorklogs'])->name('client.worklogs.management');
});

Route::group(['middleware' => ['auth', 'role:admin|client|pm']], function () {
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::get('/my-worklog', [WorklogController::class, 'viewMyWorklog'])->name('my-worklog');

    Route::get('/api/pm/{project_id}/tasks', [PmController::class, 'listTasks']);
    Route::get('/api/pm/{project_id}/epics', [PmController::class, 'listEpics']);
    Route::post('/api/pm/{project_id}/tasks/store', [PmController::class, 'storeTask']);
    Route::put('/api/pm/{project_id}/tasks/{task_id}/update', [PmController::class, 'updateTask']);
    Route::delete('/api/pm/{project_id}/tasks/{task_id}/destroy', [PmController::class, 'softDeleteTask']);
    Route::post('/api/pm/{project_id}/tasks/{task_id}/worklog', [WorklogController::class, 'store']);
    Route::get('/api/my-worklog', [WorklogController::class, 'getMyWorklogs']);
    Route::get('/api/tenant-worklog', [WorklogController::class, 'getTenantWorklogs']);
    Route::put('/api/worklog/{worklog_id}/update', [WorklogController::class, 'updateWorklog']);
    Route::delete('/api/worklog/{worklog_id}/destroy', [WorklogController::class, 'softDeleteWorklog']);

    Route::prefix('pm/{project_id}')->group(function () {
        Route::get('/task', [PmController::class, 'listTasks'])->name('pm.task');
        Route::get('/member', [PmController::class, 'listMembers'])->name('pm.member');
        Route::post('/member/update', [PmController::class, 'updateMembers'])->name('pm.member.update');
        Route::get('/chart', [PmController::class, 'viewChart'])->name('pm.chart');
    });
});

Route::group(['middleware' => ['auth', 'role:pm|staff']], function () {    
    Route::get('/api/staff/{project_id}/tasks', [StaffController::class, 'listTasks']);
    Route::put('/api/staff/{project_id}/tasks/{task_id}/update', [StaffController::class, 'updateTask']);

    Route::post('/api/staff/{project_id}/tasks/{task_id}/worklog', [WorklogController::class, 'store']);
    
    Route::prefix('staff/{project_id}')->group(function () {
        Route::get('/task', [StaffController::class, 'listTasks'])->name('staff.task');
        Route::get('/task/{task_id}/edit', [StaffController::class, 'listTasks'])->name('task.edit');
        Route::get('/task/{task_id}/destroy', [StaffController::class, 'listTasks'])->name('task.destroy');
        Route::get('/task', [StaffController::class, 'listTasks'])->name('staff.task');
        Route::get('/member', [StaffController::class, 'listMembers'])->name('staff.member');
        Route::get('/chart', [StaffController::class, 'viewChart'])->name('staff.chart');
    });
});

Route::group(['middleware' => ['auth', 'role:user']], function () {
    // Route::get('tasks', [TaskController::class, 'index']);
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



require __DIR__ . '/auth.php';
