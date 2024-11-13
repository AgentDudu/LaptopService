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
Route::get('/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index');
Route::get('/teknisi/create', [TeknisiController::class, 'create'])->name('teknisi.create');
Route::post('/teknisi', [TeknisiController::class, 'store'])->name('teknisi.store');
Route::get('/teknisi/{id}/edit', [TeknisiController::class, 'edit'])->name('teknisi.edit');
Route::put('/teknisi/{id}', [TeknisiController::class, 'update'])->name('teknisi.update');
Route::delete('/teknisi/{id}', [TeknisiController::class, 'destroy'])->name('teknisi.destroy');

// Pelanggan-----------------------------------------------------------------------------------------
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

// Laptop--------------------------------------------------------------------------------------------
Route::get('/laptop', [LaptopController::class, 'index'])->name('laptop.index');
Route::get('/laptop/create', [LaptopController::class, 'create'])->name('laptop.create');
Route::post('/laptop', [LaptopController::class, 'store'])->name('laptop.store');
Route::get('/laptop/{id}/edit', [LaptopController::class, 'edit'])->name('laptop.edit');
Route::put('/laptop/{id}', [LaptopController::class, 'update'])->name('laptop.update');
Route::delete('/laptop/{id}', [LaptopController::class, 'destroy'])->name('laptop.destroy');

// Jasa Servis---------------------------------------------------------------------------------------
Route::get('/jasaServis', [JasaServisController::class, 'index'])->name('jasaServis.index');
Route::get('/jasaServis/create', [JasaServisController::class, 'create'])->name('jasaServis.create');
Route::post('/jasaServis', [JasaServisController::class, 'store'])->name('jasaServis.store');
Route::get('/jasaServis/{id}/edit', [JasaServisController::class, 'edit'])->name('jasaServis.edit');
Route::put('/jasaServis/{id}', [JasaServisController::class, 'update'])->name('jasaServis.update');
Route::delete('/jasaServis/{id}', [JasaServisController::class, 'destroy'])->name('jasaServis.destroy');

// Resource--------------------------------------------------------------------------------------------
Route::resource('teknisi', TeknisiController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('laptop', LaptopController::class);
Route::resource('jasaServis', JasaServisController::class);

// Transaksi Servis
Route::resource('transaksiServis', TransaksiServisController::class);
Route::post('/transaksiServis/bayar', [TransaksiServisController::class, 'bayar'])->name('transaksiServis.bayar');
Route::get('/transaksiServis/{id}/cetakNota', [TransaksiServisController::class, 'cetakNota'])->name('transaksiServis.cetakNota');
Route::post('/transaksiServis/sendInvoiceToWhatsapp', [TransaksiServisController::class, 'sendInvoiceToWhatsapp']);

Route::middleware(['auth'])->group(function(){
     // Modifikasi Route Sparepart untuk diarahkan ke transaksi_sparepart
     Route::get('/sparepart', function () {
        return redirect()->route('transaksi_sparepart.index');
    })->name('transaksi_sparepart.index');

    // Transaksi Routes
    Route::resource('transaksiServis', TransaksiServisController::class)->names('transaksiServis');

    // Untuk transaksi_sparepart, gunakan hanya satu resource route
    Route::resource('transaksi_sparepart', TransaksiSparepartController::class) ->names('transaksi_sparepart');   // Menetapkan nama rute

    // Additional Routes
    Route::delete('/transaksi_sparepart/{id_transaksi_sparepart}', [TransaksiSparepartController::class, 'destroy'])->name('transaksi_sparepart.destroy');

    Route::get('/pelanggan/get/{id_pelanggan}', [PelangganController::class, 'getNoHp']);
    Route::get('/transaksi_sparepart/{id_transaksi_sparepart}/nota', [TransaksiSparepartController::class, 'nota'])->name('transaksi_sparepart.nota');

});
