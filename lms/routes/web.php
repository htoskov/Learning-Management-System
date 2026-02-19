<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubmitWorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'paid'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/progress/update', [ProgressController::class, 'update'])
    ->middleware(['auth', 'paid'])
    ->name('progress.update');

Route::middleware(['auth', 'paid'])->group(function () {
    Route::get('/submit-work', [SubmitWorkController::class, 'show'])->name('submit-work.show');
    Route::post('/submit-work', [SubmitWorkController::class, 'store'])->name('submit-work.store');
});

// Paywall pages
Route::get('/get-access', [AccessController::class, 'show'])->name('access.get');
Route::post('/checkout', [CheckoutController::class, 'create'])->name('checkout.create')->middleware('auth');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel')->middleware('auth');

// Stripe calls this (NO auth)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

require __DIR__.'/auth.php';
