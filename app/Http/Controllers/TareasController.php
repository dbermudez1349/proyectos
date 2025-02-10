<?php

namespace App\Http\Controllers;

use App\Models\tareas;
use App\Models\Proyectos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TareasController extends Controller
{
    public function index()
    {
        // Obtener todas las tareas del usuario autenticado
        $tareas = Tareas::where('usuario_id', Auth::id())->get();

        // Pasar las tareas a la vista
        return view('tareas.index', compact('tareas'));
    }

    public function create() {
        $proyectos = Proyectos::all();
        $usuarios = User::all();
        return view('tareas.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'proyecto_id' => 'required|exists:proyectos,id',
            'usuario_id' => 'required|exists:users,id',
            'fecha_limite' => 'nullable|date',
            'archivo' => 'nullable|file|max:2048',
        ]);

        $archivoPath = $request->file('archivo') ? $request->file('archivo')->store('archivos') : null;

        $tarea = Tareas::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'proyecto_id' => $request->proyecto_id,
            'usuario_id' => $request->usuario_id,
            'fecha_limite' => $request->fecha_limite,
            'archivo' => $archivoPath,
            'estado' => 'Pendiente',
        ]);

        $usuario = User::find($request->usuario_id);
        //Notification::send($usuario, new TareaAsignada($tarea));

        return redirect()->route('home')->with('success', 'Tarea creada y asignada correctamente.');
    }

    public function edit(Tareas $tarea)
    {
        $proyectos = Proyectos::all();
        $usuarios = User::all();
        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios'));
    }

    public function update(Request $request, Tareas $tarea)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
            'usuario_id' => 'required|exists:users,id',
            'estado' => 'required|in:Pendiente,Atrasada,Completada',
            'fecha_limite' => 'nullable|date',
            'archivo' => 'nullable|file|max:2048',
        ]);

        if ($request->hasFile('archivo')) {
            $archivoPath = $request->file('archivo')->store('tareas', 'public');
            $tarea->archivo = $archivoPath;
        }

        $tarea->update($request->except('archivo'));

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada.');
    }

    public function destroy(Tareas $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada.');
    }
}
