<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('profile', 'profile')->name('profile');

    Route::get('/', function(){
        return redirect('/areas');
    });

    Route::get('/areas', [AreaController::class, 'index'])->name('areas');
    Route::get('/areas/create', [AreaController::class, 'create'])->name('areas.create');
    Route::get('/areas/{areas}/edit', [AreaController::class, 'edit'])->name('areas.edit');
});

require __DIR__.'/auth.php';
