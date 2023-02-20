<?php

use App\Http\Controllers\Api\JamKerjaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function () {
    // usere
    Route::post('/update-user', [UserController::class, 'update_user']);
    Route::get('/details-user', [UserController::class, 'details_user']);

    // jam kerja
    Route::post('/masuk-kerja', [JamKerjaController::class, 'masuk_kerja']);
    Route::post('/pulang-kerja', [JamKerjaController::class, 'pulang_kerja']);
});
