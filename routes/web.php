<?php

use App\Http\Controllers\Admin\AbsenController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CrewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view("auth.login");
});

Auth::routes();

Route::prefix('dashboard')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard_index');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('index_laporan');

    // Absen
    Route::get('/absen', [AbsenController::class, 'index'])->name('index_absen');

    // Crew
    Route::get('/crew', [CrewController::class, 'index'])->name('index_crew');
    Route::get('/crew/create', [CrewController::class, 'create'])->name('create_crew');
    Route::get('/crew/cari', [CrewController::class, 'cari'])->name('cari_crew');
    Route::post('/crew/kirim', [CrewController::class, 'store'])->name('store_crew');

    // pengumuman
    Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('index_pengumuman');
    Route::post('/pengumuman/post', [AnnouncementController::class, 'post'])->name('post_pengumuman');
    Route::put('/pengumuman/update/{id}', [AnnouncementController::class, 'update'])->name('update_pengumuman');
    Route::delete('/pengumuman/delete/{id}', [AnnouncementController::class, 'destroy'])->name('delete_pengumuman');

    // Setting
    Route::get('/location&jam', [SettingController::class, 'index'])->name('index_setting');
    Route::post('/update', [SettingController::class, 'update_jamkantor'])->name('update_setting_jam_kantor');

    Route::post('/lokasi-kantor-update', [SettingController::class, 'update'])->name('lokasiKantor.update');
});
