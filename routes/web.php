<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // client related routes
    Route::resource("/clients", ClientController::class)
        ->only(["index", "store", "edit", "update", "destroy"]);

    // staff related routes
    Route::resource("/staff", StaffController::class)
        ->only(["index", "store", "edit", "update", "destroy"]);
});

require __DIR__ . '/auth.php';
