<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\SubmitWorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::post('/progress/update', [ProgressController::class, 'update'])
    ->middleware(['auth'])
    ->name('progress.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/submit-work', [SubmitWorkController::class, 'show'])->name('submit-work.show');
    Route::post('/submit-work', [SubmitWorkController::class, 'store'])->name('submit-work.store');
});
    
require __DIR__.'/auth.php';
