<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardHistoryController;

Route::get('/', [DashBoardController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('/dashboard/controls', DashboardHistoryController::class);
Route::get('/dashboard/cetak', [DashboardHistoryController::class, 'cetak']);

Route::get('/bacasuhu', [MonitoringController::class, 'bacasuhu']);
Route::get('/bacakekeruhan', [MonitoringController::class, 'bacakekeruhan']);
Route::get('/bacaph', [MonitoringController::class, 'bacaph']);
Route::get('/bacajarak', [MonitoringController::class, 'bacajarak']);
Route::get('/bacapompamasuk', [MonitoringController::class, 'bacapompamasuk']);
Route::get('/bacapompakeluar', [MonitoringController::class, 'bacapompakeluar']);

// Route untuk menyimpan nilai sensor ke database
// Route::get('/simpan/{temperature}/{turbidity}/{ph}/{jarak}/{pompa_masuk}/{pompa_keluar}', [MonitoringController::class, 'simpan']);

Route::get('/simpan/{device_id}/{temperature}/{humidity}', [MonitoringController::class, 'simpan']);
Route::get('/bacahumidity', [MonitoringController::class, 'bacahumidity']);

Route::get('/statistik', [MonitoringController::class, 'statistik']);
Route::get('/tabel-riwayat', [MonitoringController::class, 'tabelRiwayat']);

