<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RealtimeController;
use App\Http\Controllers\SwitchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return redirect('login');});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('switches')->group(function() {
        Route::get('/', [SwitchController::class, 'index'])->name('switches');
        Route::patch('/update-switch/{switches}', [SwitchController::class, 'updateSwitch']);
        Route::patch('/update-name-switch/{switchId}', [SwitchController::class, 'updateNameSwitch']);
    });
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::patch('/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::patch('/update-connection', [ProfileController::class, 'updateConnection'])->name('connection.update');
        Route::patch('/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });
    Route::prefix('monitoring')->group(function () {
        Route::get('/realtime', [RealtimeController::class, 'index'])->name('realtime');
        Route::patch('/realtime/update-taxes/{taxId}', [RealtimeController::class, 'updateTaxes'])->name('taxes.update');
        Route::get('/history', [HistoryController::class, 'index'])->name('history');
    });
});