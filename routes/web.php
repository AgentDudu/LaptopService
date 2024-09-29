<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('auth.login');
})->name('home');

require __DIR__.'/auth.php';

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard'); // Or your desired view for the dashboard
//     })->name('dashboard');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/dashboard', function () {
    if (auth()->user()->status == 'Pemilik') {
        return view('pemilik.dashboard');
    } else {
        return view('pegawai.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/pemilik', [PemilikController::class, 'index'])->middleware('auth')->name('pemilik.dashboard');
// Route::get('/pegawai', [PegawaiController::class, 'index'])->middleware('auth')->name('pegawai.dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//     Route::resource('customers', CustomerController::class);
//     Route::resource('users', UserController::class);
//     Route::resource('laptops', LaptopController::class);
//     Route::resource('services', ServiceController::class);
//     Route::resource('warranties', WarrantyController::class);
//     Route::resource('service_transactions', ServiceTransactionController::class);
//     Route::get('service_transactions', [ServiceTransactionController::class, 'index'])->name('service_transactions.index');
//     Route::get('service_transactions/getLaptopDetails/{laptopId}', [ServiceTransactionController::class, 'getLaptopDetails']);
//     Route::get('service_transactions/{transaction}/pay', [ServiceTransactionController::class, 'showPayForm'])->name('service_transactions.pay');
//     Route::post('service_transactions/{transaction}/pay', [ServiceTransactionController::class, 'processPayment'])->name('service_transactions.processPayment');
//     Route::get('service_transactions/view/{transaction}', [ServiceTransactionController::class, 'show'])->name('service_transactions.show');
//     Route::get('service_transactions/print/{transaction}', [ServiceTransactionController::class, 'print'])->name('service_transactions.print');
// });


// // Route::middleware(['auth', 'admin'])->group(function () {
// //     Route::get('admin/dashboard', [HomeController::class, 'index']);

// //     Route::get('/admin/products', [ProductController::class, 'index'])->name('admin/products');
// //     Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin/products/create');
// //     Route::post('/admin/products/save', [ProductController::class, 'save'])->name('admin/products/save');
// //     Route::get('/admin/products/edit/{id}', [ProductController::class, 'edit'])->name('admin/products/edit');
// //     Route::put('/admin/products/edit/{id}', [ProductController::class, 'update'])->name('admin/products/update');
// //     Route::get('/admin/products/delete/{id}', [ProductController::class, 'delete'])->name('admin/products/delete');
// // });