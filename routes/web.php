<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('admin', function () {
    return Inertia::render('Admin');
})->name('admin')->middleware(['admin']); // Assuming admin middleware exists

Route::post('/pay', [PaymentController::class, 'pay'])->middleware('auth.custom');

Route::post('/deposit', [PaymentController::class, 'deposit'])->middleware('auth.custom');

Route::post('/withdraw', [PaymentController::class, 'withdraw'])->middleware('auth.custom');


require __DIR__ . '/settings.php';
