<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\ScrumbanController;

Route::get('/dashboard/summary', [DashboardController::class, 'index'])->name('dashboard.summary');
Route::get('/scrumban/dashboard-summary', [ScrumbanController::class, 'getSummary']);
