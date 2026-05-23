<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityUpdateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (or login if not authenticated)
Route::get('/', fn () => redirect()->route('dashboard'));

// ── Authentication ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Protected routes ────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ActivityController::class, 'index'])->name('dashboard');

    Route::post('/activities',                      [ActivityController::class, 'store'])->name('activities.store');
    Route::delete('/activities/{activity}',         [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::post('/activities/{activity}/updates',   [ActivityUpdateController::class, 'store'])->name('updates.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});
