<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\DailyReportController;

Route::post('v1/login', [UserController::class, 'login']);
Route::post('v1/register', [UserController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function () {
    // user
    Route::post('v1/update-user', [UserController::class, 'update_user']);
    Route::get('v1/details-user', [UserController::class, 'details_user']);

    // Absensi
    Route::post('v1/absent-entry', [AbsenController::class, 'absentEntry']);
    Route::put('v1/absent-home', [AbsenController::class, 'absentHome']);

    //daily report
    Route::get('v1/daily-report', [DailyReportController::class, 'getDailyReport']);
    Route::post('v1/daily-report', [DailyReportController::class, 'storeDailyReport']);
    Route::put('v1/daily-report/{id}', [DailyReportController::class, 'updateDailyReport']);
});
