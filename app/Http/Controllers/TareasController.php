<?php

namespace App\Http\Controllers;

use App\Models\tareas;
use App\Models\proyectos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TareaCreada;
use Illuminate\Support\Facades\Notification;


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
        $proyectos = proyectos::all();
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
        $usuario->notify(new TareaCreada($tarea));
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

    public function completar(Request $request, Tareas $tarea) {
        if (auth()->user()->id !== $tarea->usuario_id) {
            abort(403);
        }

        $request->validate([
            'comentario' => 'required|string',
            'archivos.*' => 'nullable|file|max:2048'
        ]);

        $archivosPaths = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $archivosPaths[] = $archivo->store('archivos');
            }
        }

        $tarea->update([
            'estado' => 'Completada'
        ]);

        return redirect()->route('home')->with('success', 'Tarea completada con éxito.');
    }

    public function show(tareas $tarea) {
        if (auth()->user()->id !== $tarea->usuario_id) {
            abort(403);
        }
        return view('tareas.show', compact('tarea'));
    }

    public function destroy(Tareas $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada.');
    }

    public function archivar(tareas $tarea) {
        if ($tarea->estado !== 'Completada') {
            return redirect()->back()->with('error', 'Solo se pueden archivar tareas completadas.');
        }
        $tarea->update(['archivar' => 1]);
        return redirect()->route('home')->with('success', 'Tarea archivada con éxito.');
    }
}
