<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\{
    PenggunaController,
    PerangkatController,
    MonitoringController,
    LogNotifikasiController,
    AdminDashboardController
};
use App\Http\Controllers\Petugas\{
    PetugasDashboardController,
    PetugasMonitoringController,
    PetugasLogNotifikasiController
};
use App\Http\Controllers\Warga\{
    WargaDashboardController,
    WargaMonitoringController
};

// Auth Routes
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('login')->with('success', 'Berhasil logout.');
})->name('logout');

// Admin Routes
Route::prefix('admin')->middleware(['ensureLogin', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/pengguna', [PenggunaController::class, 'index'])
        ->name('admin.pengguna.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])
        ->name('admin.pengguna.create');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])
        ->name('admin.pengguna.edit');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])
        ->name('admin.pengguna.destroy');

    Route::get('/perangkat', [PerangkatController::class, 'index'])
        ->name('admin.perangkat.index');
    Route::get('/perangkat/create', [PerangkatController::class, 'create'])
        ->name('admin.perangkat.create');
    Route::get('/perangkat/{id}/edit', [PerangkatController::class, 'edit'])
        ->name('admin.perangkat.edit');
    Route::delete('/perangkat/{id}', [PerangkatController::class, 'destroy'])
        ->name('admin.perangkat.destroy');

    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('admin.monitoring.index');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])
        ->name('admin.monitoring.show');

    Route::get('/log-notifikasi', [LogNotifikasiController::class, 'index'])
        ->name('admin.log-notifikasi.index');
});

// Petugas Routes
Route::prefix('petugas')->middleware(['ensureLogin', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])
        ->name('petugas.dashboard');

    Route::get('/monitoring', [PetugasMonitoringController::class, 'index'])
        ->name('petugas.monitoring.index');
    Route::get('/monitoring/{id}', [PetugasMonitoringController::class, 'show'])
        ->name('petugas.monitoring.show');

    Route::get('/log-notifikasi', [PetugasLogNotifikasiController::class, 'index'])
        ->name('petugas.log-notifikasi.index');
});

// Warga Routes
Route::prefix('warga')->middleware(['ensureLogin', 'role:warga'])->group(function () {
    Route::get('/dashboard', [WargaDashboardController::class, 'index'])
        ->name('warga.dashboard');

    Route::get('/monitoring', [WargaMonitoringController::class, 'index'])
        ->name('warga.monitoring.index');
    Route::get('/monitoring/{id}', [WargaMonitoringController::class, 'show'])
        ->name('warga.monitoring.show');
});
