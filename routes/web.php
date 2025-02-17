<?php

use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\TareasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tablero', [HomeController::class, 'tablero'])->name('tablero');
    Route::resource('proyectos', ProyectosController::class);
    Route::resource('tareas', TareasController::class);

    Route::post('/tareas/{tarea}/completar', [TareasController::class, 'completar'])->name('tareas.completar');
    Route::post('/tareas/{tarea}/archivar', [TareasController::class, 'archivar'])->name('tareas.archivar');

    Route::post('/tareas/{tarea}/actividad', [TareasController::class, 'agregarActividad'])->middleware('auth')->name('tareas.agregarActividad');
    Route::get('/archivo', [TareasController::class, 'archivo'])->name('tareas.archivo');
    Route::patch('/tareas/restaurar/{tarea}', [TareasController::class, 'restaurar'])->name('tareas.restaurar');

    Route::post('/logout', function() {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});



