<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view ('admin.dashboard');
})->name('dashboard');

//Gestion de roles
Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

//Gestion de usuarios
Route::resource('usuarios', App\Http\Controllers\Admin\UserController::class);