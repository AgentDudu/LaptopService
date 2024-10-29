<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\TransaksiSparepartController;
use App\Http\Controllers\TransaksiServisController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\JasaServisController;
use App\Http\Controllers\ServisController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Login Register------------------------------------------------------------------------------------
Route::get('/', function () {
    return view('auth.login');
})->name('home');

require __DIR__ . '/auth.php';

Route::get('dashboard', function () {
    if (auth()->user()->status === 'Pemilik' || auth()->user()->status === 'Pegawai') {
        return view('dashboard.dashboard-user');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Teknisi-------------------------------------------------------------------------------------------
Route::resource('teknisi', TeknisiController::class);

// Pelanggan-----------------------------------------------------------------------------------------
Route::resource('pelanggan', PelangganController::class);

// Laptop--------------------------------------------------------------------------------------------
Route::resource('laptop', LaptopController::class);

// Jasa Servis---------------------------------------------------------------------------------------
Route::resource('jasaServis', JasaServisController::class);

// Lain-lain (ini blm fix, hanya perlu dideklarasikan aja biar yg lain ga error)
Route::get('/servis', [ServisController::class, 'index'])->name('servis.index');

// Protected Routes (routes that require authentication)
Route::middleware(['auth'])->group(function () {

    // Modifikasi Route Sparepart untuk diarahkan ke transaksi_sparepart
    Route::get('/sparepart', function () {
        return redirect()->route('transaksi_sparepart.index');
    })->name('transaksi_sparepart.index');

    // Modifikasi Route Servis untuk diarahkan ke transaksi_servis
    Route::get('/servis', function () {
        return redirect()->route('transaksi_servis.index');
    })->name('transaksi_servis.index');

    // Transaksi Routes
    Route::resource('transaksi_servis', TransaksiServisController::class)->names('transaksi_servis');

    // Untuk transaksi_sparepart, gunakan hanya satu resource route
    Route::resource('transaksi_sparepart', TransaksiSparepartController::class)
        ->except(['show'])   // Mengecualikan metode show
        ->names('transaksi_sparepart');   // Menetapkan nama rute

    // Additional Routes
    Route::delete('/transaksi_sparepart/{id_transaksi_sparepart}', [TransaksiSparepartController::class, 'destroy'])->name('transaksi_sparepart.destroy');

    Route::get('/pelanggan/get/{id_pelanggan}', [PelangganController::class, 'getNoHp']);
    Route::get('/transaksi_sparepart/jual/{id_transaksi_sparepart}', [TransaksiSparepartController::class, 'jual'])->name('transaksi_sparepart.jual');
});
