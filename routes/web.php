<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return redirect(route('register'));
});

Route::get('/dashboard', [DashboardController::class, 'show'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resources([
        'appointments' => AppointmentController::class,
        'medicines' => MedicineController::class
    ]);

    Route::resource('admin',AdminController::class)->only(['index','destroy']);

    Route::get('history',[HistoryController::class, 'showHistory']);

    Route::get('appointments/{appointment}/payment-details', [AppointmentController::class, 'showPayment'])->name('appointments.paymentdetails');
    Route::get('appointments/{appointment}/process-invoice', [AppointmentController::class, 'processInvoice'])->name('appointments.invoice');
    Route::post('appointments/{appointment}/payment', [AppointmentController::class, 'payment'])->name('appointments.payment');
});

require __DIR__ . '/auth.php';
