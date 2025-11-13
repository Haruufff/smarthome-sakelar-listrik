<?php

use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\MonitoringApiController;
use App\Http\Controllers\Api\SwitchesApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {return $request->user();})->middleware('auth:sanctum');

Route::prefix('monitoring')->group( function() {
    Route::get('/history/{month}', [MonitoringApiController::class, 'getMonthlyData']);
    Route::get('/monthly-average', [MonitoringApiController::class, 'getMonthlyAverage']);
    Route::get('/monthly-total-price', [MonitoringApiController::class, 'getMonthlyTotalPrice']);
    Route::post('/post-energy-data', [MonitoringApiController::class, 'postMonitoringData']);
    Route::get('/realtime-chart', [MonitoringApiController::class, 'getRealtimeChart']);
    Route::get('/realtime-latest-data', [MonitoringApiController::class, 'getRealtimeMonitoringData']);
    Route::get('/check-reset-kwh', [MonitoringApiController::class, 'checkResetkWh']);
    Route::post('/confirm-reset', [MonitoringApiController::class, 'confirmReset']);
});

Route::prefix('switches')->group(function() {
    Route::get('/', [SwitchesApiController::class, 'getSwitches']);
});

Route::get('/wifi-config', [ConfigController::class, 'getConfig']);