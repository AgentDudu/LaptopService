<?php

use App\Http\Controllers\TransaksiSparepartController;
use App\Http\Controllers\TransaksiServisController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\JasaServisController;
use App\Http\Controllers\LaporanController;
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

// Resource TEKNISI PELANGGAN LAPTOP JASASERVIS--------------------------------------------------------------------------------------------
Route::resource('teknisi', TeknisiController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('laptop', LaptopController::class);
Route::resource('jasaServis', JasaServisController::class);

// Transaksi Servis
Route::resource('transaksiServis', TransaksiServisController::class);
Route::post('/transaksiServis/bayar', [TransaksiServisController::class, 'bayar'])->name('transaksiServis.bayar');
Route::get('/transaksiServis/{id}/cetakNota', [TransaksiServisController::class, 'cetakNota'])->name('transaksiServis.cetakNota');

// Transaksi Sparepart
Route::resource('transaksi_sparepart', TransaksiSparepartController::class)->names([
    'index' => 'transaksi_sparepart.index',
    'create' => 'transaksi_sparepart.create',
    'store' => 'transaksi_sparepart.store',
    'show' => 'transaksi_sparepart.show',
    'edit' => 'transaksi_sparepart.edit',
    'update' => 'transaksi_sparepart.update',
    'destroy' => 'transaksi_sparepart.destroy',
]);

// Additional routes Transaksi Sparepart
Route::get('/pelanggan/get/{id_pelanggan}', [PelangganController::class, 'getNoHp'])->name('pelanggan.get');
Route::get('/transaksi_sparepart/{id_transaksi_sparepart}/nota', [TransaksiSparepartController::class, 'nota'])->name('transaksi_sparepart.nota');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
