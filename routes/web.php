<?php

use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\TareasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/mis-tareas', [HomeController::class, 'misTareas'])->name('misTareas');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/tablero', [HomeController::class, 'tablero'])->name('tablero');
    Route::resource('proyectos', ProyectosController::class);
    Route::get('/tareas/buscar-proyectos', [ProyectosController::class, 'buscarProyectos']);
    Route::get('/proyectos-listar', [ProyectosController::class, 'listar'])->name('listar');
    Route::resource('tareas', TareasController::class);
    Route::get('/buscar-tareas', [TareasController::class, 'buscarTareas']);
    Route::post('/tareas/consultarTareas', [TareasController::class, 'consultarTareas']);
    Route::get('/mis-proyectos-listar', [TareasController::class, 'buscarMisTareas']);
    Route::get('/detalle-tarea/{id}', [TareasController::class, 'detalleTarea']);
    Route::get('/actualizar-actividad/{id}', [TareasController::class, 'listarActividad']);
    Route::get('/finalizar-tarea/{id}', [TareasController::class, 'finalizarTarea']);
    Route::post('/revertirEstado', [TareasController::class, 'revertirEstado']);
    Route::get('/ver-detalle-reversion/{id}', [TareasController::class, 'verReversion']);
    

    Route::post('/tareas/{tarea}/completar', [TareasController::class, 'completar'])->name('tareas.completar');
    Route::post('/tareas/{tarea}/archivar', [TareasController::class, 'archivar'])->name('tareas.archivar');

    // Route::post('/tareas/{tarea}/actividad', [TareasController::class, 'agregarActividad'])->middleware('auth')->name('tareas.agregarActividad');

    Route::post('/agregarActividad', [TareasController::class, 'agregarActividad']);
    Route::post('/agregarObservacion', [TareasController::class, 'agregarObservacion']);

    Route::get('/archivo', [TareasController::class, 'archivo'])->name('tareas.archivo');
    Route::patch('/tareas/restaurar/{tarea}', [TareasController::class, 'restaurar'])->name('tareas.restaurar');

    Route::get('/actividades/{id}/{archivoIndex}', [TareasController::class, 'descargarArchivo'])
    ->name('actividades.descargar');

    Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario.usuario');
    Route::get('/listado-usuario', [UsuarioController::class, 'listar']);
    Route::post('/guardar-usuario', [UsuarioController::class, 'guardar']);
    Route::get('/resetear-password/{id}', [UsuarioController::class, 'resetearPassword']);
    Route::get('/editar-usuario/{id}', [UsuarioController::class, 'editar']);
    Route::put('/actualizar-usuario/{id}', [UsuarioController::class, 'actualizar']);
    Route::post('/cambiar-clave', [UsuarioController::class, 'cambiarClave']);


    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/listado-rol', [RolController::class, 'listar']);
    Route::get('/ver-permisos-roles/{id}', [RolController::class, 'verPermisos']);
    Route::get('/eliminar-permiso/{idpermiso}/{idrol}', [RolController::class, 'eliminarPermiso']);
    Route::post('/guardar-permiso', [RolController::class, 'guardarPermiso']);

    Route::post('/logout', function() {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');

    return "Cleared!";

 });



// Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('auth');
