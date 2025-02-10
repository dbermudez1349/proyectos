<?php

use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\TareasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::resource('proyectos', ProyectosController::class)->middleware('auth');
Route::resource('tareas', TareasController::class)->middleware('auth');
Route::resource('tareas.realizar', TareasController::class)->middleware('auth');

Route::post('/logout', function() {
    Auth::logout();
    return redirect('/login');
})->name('logout');
