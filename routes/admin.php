<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view ('admin.dashboard');
})->name('dashboard');

//Gestion de roles
Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

//Gestion de usuarios
Route::resource('usuarios', App\Http\Controllers\Admin\UserController::class);

//Gestion de estudiantes
Route::resource('estudiantes', App\Http\Controllers\Admin\StudentController::class)->parameters([
    'estudiantes' => 'student'
]);

//Gestion de tutores
Route::get('tutors/{tutor}/schedule', [App\Http\Controllers\Admin\TutorController::class, 'schedule'])->name('tutors.schedule');
Route::resource('tutors', App\Http\Controllers\Admin\TutorController::class);

// Gestion de citas (Tutorias)
Route::get('appointments/{appointment}/session', [App\Http\Controllers\Admin\AppointmentController::class, 'session'])->name('appointments.session');
Route::get('appointments/{appointment}/detail', [App\Http\Controllers\Admin\AppointmentController::class, 'detail'])->name('appointments.detail');
Route::resource('appointments', App\Http\Controllers\Admin\AppointmentController::class);