<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Grupo de rutas del panel de administración. Protegido únicamente con
| el middleware de autenticación estándar (auth) — sin revisión de roles.
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Redirige /admin → /admin/dashboard
    Route::get('/', fn() => redirect()->route('admin.dashboard'));

    // Dashboard del admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

});
