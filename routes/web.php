<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     // --- Rutas de Notificaciones ---
     Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
     Route::patch('/notifications/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
     // ---------------------------------

     // --- 1. Rutas Específicas PRIMERO ---
     // ... (resto de tus rutas) ...
     Route::get('/customers/import', [CustomerImportController::class, 'create'])->name('customers.import.create');
     Route::post('/customers/import', [CustomerImportController::class, 'store'])->name('customers.import.store');

     // --- 2. Rutas Genéricas (Resource) DESPUÉS ---
     Route::resource('customers', CustomerController::class);
});

require __DIR__.'/auth.php';
