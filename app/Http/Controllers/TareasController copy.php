<?php

namespace App\Http\Controllers;

use App\Models\tareas;
use App\Models\proyectos;
use App\Models\User;
use App\Models\actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TareaCreada;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TareasController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth()->user()->hasPermissionTo('tablero de tareas'))
        {
            abort(403, 'No tienes acceso a esta seccion.');
        }
        $view = $request->query('view', 'kanban'); // Vista por defecto: Kanban
        $tareas = tareas::with('usuarios')->get();
        return view('tareas.index', compact('tareas', 'view'));
    }

    public function create() {
        if(!Auth()->user()->hasPermissionTo('crear tareas'))
        {
            abort(403, 'No tienes acceso a esta seccion.');
        }
        $proyectos = proyectos::all();
        $usuarios = User::all();
        return view('tareas.create', compact('proyectos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'usuarios2' => 'required|array', // Validar que el campo 'usuarios2' es un array
            'usuarios2.*' => 'integer|exists:users,id', // Validar que cada ID sea válido
            'proyecto_id' => 'required|integer',
            'fecha_limite' => 'nullable|date',
        ]);

         // Los usuarios seleccionados están en el array 'usuarios2'
        $usuariosIds = $validated['usuarios2'];

        // Crear la tarea
        $tarea = new tareas();
        $tarea->titulo = $validated['titulo'];
        $tarea->descripcion = $validated['descripcion'];
        $tarea->proyecto_id = $validated['proyecto_id'];
        $tarea->fecha_limite = $validated['fecha_limite'];
        $tarea->save();

        // Asignar usuarios a la tarea
        foreach ($usuariosIds as $usuarioId) {
            // Realizar alguna operación con cada usuario, como asociarlo a la tarea
            $tarea->usuarios()->attach($usuarioId);
            $usuario = User::find($usuarioId);
            $usuario->notify(new TareaCreada($tarea));
            Notification::send($usuario, new TareaAsignada($tarea));
        }

        //$usuario = User::find($request->usuario_id);
        //$usuario->notify(new TareaCreada($tarea));
        //Notification::send($usuario, new TareaAsignada($tarea));

        return redirect()->route('home')->with('success', 'Tarea creada y asignada correctamente.');
    }

    public function edit(Tareas $tarea)
    {
        $proyectos = Proyectos::all();
        $usuarios = User::all();
        return view('tareas.edit', compact('tarea', 'proyectos', 'usuarios'));
    }

    public function show(tareas $tarea) {
        if (
            !$tarea->usuarios->contains(auth()->id()) && // Si el usuario no está asignado
            !Auth::user()->hasPermissionTo('ver tareas asignadas') && // Y no tiene este permiso
            !Auth::user()->hasPermissionTo('ver tareas detalladas') // Y tampoco este
        ) {
            abort(403, 'No tienes acceso a esta tarea.');
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

    public function agregarActividad(Request $request, tareas $tarea) {
        $request->validate([
            'comentario' => 'nullable|string',
            'archivos.*' => 'nullable|file|max:2048'
        ]);

        $archivosPaths = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $archivosPaths[] = $archivo->store('archivos','public');
            }
        }

        $tarea->actividades()->create([
            'usuario_id' => auth()->id(),
            'comentario' => $request->comentario,
            'archivos' => count($archivosPaths) ? $archivosPaths : null,
            'tipo' => $request->comentario ? 'Comentario' : 'Archivo',
        ]);

        return back()->with('success', 'Actividad añadida.');
    }

    public function completar(Request $request, tareas $tarea) {
        if (! $tarea->usuarios->contains(auth()->id())) {
            return abort(403);
        }

        $tarea->update(['estado' => 'Completada']);

        $tarea->actividades()->create([
            'usuario_id' => auth()->id(),
            'comentario' => 'Tarea marcada como completada.',
            'tipo' => 'Estado Cambiado',
        ]);

        return back()->with('success', 'Tarea completada con éxito.');
    }

    public function archivo(Request $request)
    {
        $tareasArchivadas = tareas::where('archivar', true)->with('usuarios')->get();
        return view('tareas.archivos', compact('tareasArchivadas'));
    }

    public function restaurar(tareas $tarea)
    {
        $tarea->update(['estado' => 'pendiente']);
        return redirect()->route('tareas.archivo')->with('success', 'Tarea restaurada con éxito.');
    }

    public function descargarArchivo($id)
    {
        $actividad = actividad::findOrFail($id);

        // Verificar si hay archivos adjuntos
        if (!$actividad->archivos || count($actividad->archivos) === 0) {
            abort(404, 'No hay archivos disponibles para esta actividad.');
        }

        // Tomar el primer archivo del array (si hay varios, se puede modificar la lógica)
        $archivo = $actividad->archivos[0];

        // Verificar que el archivo existe en el storage
        if (!Storage::exists($archivo)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        // Generar un nombre limpio para la descarga
        $slug = Str::slug(pathinfo($archivo, PATHINFO_FILENAME)) . '.' . pathinfo($archivo, PATHINFO_EXTENSION);

        return Storage::download($archivo, $slug);
    }

}
