<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'tasks' => auth()->check() ? \App\Models\Task::where('assigned_to', auth()->id())
            ->whereIn('status', ['not_started', 'in_progress', 'blocked'])
            ->with('project')
            ->orderBy('due_date')
            ->get() : []
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::get('/projects/{project}/kanban', [\App\Http\Controllers\ProjectController::class, 'kanban'])->name('projects.kanban');
    Route::resource('project-members', \App\Http\Controllers\ProjectMemberController::class)->only(['store', 'destroy']);
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Task Comments
    Route::post('/tasks/{task}/comments', [\App\Http\Controllers\TaskCommentController::class, 'store'])->name('task-comments.store');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\TaskCommentController::class, 'destroy'])->name('task-comments.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Time Logs
    Route::post('/tasks/{task}/time-logs', [\App\Http\Controllers\TimeLogController::class, 'store'])->name('time-logs.store');
    Route::delete('/time-logs/{timeLog}', [\App\Http\Controllers\TimeLogController::class, 'destroy'])->name('time-logs.destroy');

    // Reports
    Route::get('/reports/workload', [\App\Http\Controllers\ReportController::class, 'workload'])->name('reports.workload');
    Route::get('/reports/analytics', [\App\Http\Controllers\ReportController::class, 'analytics'])->name('reports.analytics');

    // Dashboard Monitoring
    Route::get('/dashboard-monitor', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.monitor');
    Route::get('/dashboard/workload', [\App\Http\Controllers\DashboardController::class, 'workload'])->name('dashboard.workload');
    Route::get('/dashboard/tasks', [\App\Http\Controllers\DashboardController::class, 'tasks'])->name('dashboard.tasks');
    Route::get('/api/dashboard/statistics', [\App\Http\Controllers\DashboardController::class, 'getStatics']);
    Route::get('/api/dashboard/workload', [\App\Http\Controllers\DashboardController::class, 'getWorkloadStatics']);

    // Gantt
    Route::get('/projects/{project}/gantt', [\App\Http\Controllers\ProjectController::class, 'gantt'])->name('projects.gantt');

    // Activity Logs
    Route::get('/activity', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity.index');
});

require __DIR__.'/auth.php';
