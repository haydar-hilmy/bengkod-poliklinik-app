<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalPeriksaController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PoliController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pasien\PoliController as PasienPoliController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('polis', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
    Route::get('obat/{id}/detail', [ObatController::class, 'detail'])
        ->name('obat.detail');
    Route::post('obat/{id}/update-stok', [ObatController::class, 'updateStok'])
        ->name('obat.updateStok');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class);
    Route::resource('periksa-pasien', PeriksaPasienController::class);
    Route::get('periksa-pasien/create/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])
        ->name('dokter.riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])
        ->name('dokter.riwayat-pasien.show');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', function () {
        return view('pasien.dashboard');
    })->name('pasien.dashboard');

    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PasienPoliController::class, 'submit'])->name('pasien.daftar.submit');
});

