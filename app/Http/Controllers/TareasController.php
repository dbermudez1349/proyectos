<?php

namespace App\Http\Controllers;
use App\Models\TareaUsuario;
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
       
        $area=\DB::table('area')
        ->where('estado',1)
        ->get();
        return view('tareas.index', ['area'=>$area]);
    }

    public function create() {
        if(!Auth()->user()->hasPermissionTo('crear tareas'))
        {
            abort(403, 'No tienes acceso a esta seccion.');
        }
        // $proyectos = proyectos::all();
        $usuarios = User::all();

        $area=\DB::table('area')
        ->where('estado',1)
        ->get();

        return view('tareas.create', compact('area', 'usuarios'));
    }
    public function buscarTareas(Request $request){
       
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $text=mb_strtoupper($search);
           
            $data = \DB::table('tareas')
            ->where(function ($c) use ($text) {
                $c->where('titulo', 'like', '%' . $text . '%');
            })
            ->select(\DB::raw("CONCAT(titulo, ' - ', descripcion) AS detalle"),'id')
            ->take(50)
            ->get();
        }        

        return response()->json($data);
    }

    public function consultarTareas(Request $request){
        try{
            $id_tarea=0;
            $id_area=[];
            $estado=$request->estado;

            if(isset($request->titulo)){
                $id_tarea=$request->titulo;
            }

             if(isset($request->areas)){
                $id_area=$request->areas;
            }

            $tareas=\DB::table('tareas as t')
                ->leftJoin('tarea_usuario as tu', 'tu.tarea_id','t.id')
                ->leftJoin('users as u', 'u.id','tu.usuario_id')
                ->leftJoin('area as a', 'a.id','u.id_area')
                ->where(function($query) use($estado, $id_area, $id_tarea) {
                    if(sizeof($id_area)>0){
                        $query->whereIn('u.id_area',$id_area);
                    }

                    if($id_tarea>0){
                        $query->where('t.id',$id_tarea);
                    }

                    if($estado!="Todos"){
                         $query->where('t.estado',$estado);
                    }
                })
              
                ->select('t.id', 't.titulo', 't.descripcion', 't.estado','a.descripcion as area','t.fecha_limite')
                ->get()
                ->groupBy('id') // agrupa por id de tarea
                ->map(function ($items) {
                    $tarea = $items->first(); // toma los datos generales de la tarea
                    return [
                        'id' => $tarea->id,
                        'titulo' => $tarea->titulo,
                        'descripcion' => $tarea->descripcion,
                        'estado' => $tarea->estado,
                        'fecha_limite' => $tarea->fecha_limite,
                        'areas' => $items->pluck('area')->unique()->values(), // crea array de áreas sin repetir
                    ];
                })
                ->values();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$tareas
            ]);

        }catch (\Throwable $e) {
            \Log::error('TareasController => consultarTareas => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }
    }

    public function buscarMisTareas(){
        try{
            $tareas=\DB::table('tareas as t')
                ->leftJoin('tarea_usuario as tu', 'tu.tarea_id','t.id')
                ->leftJoin('users as u', 'u.id','tu.usuario_id')
                ->leftJoin('area as a', 'a.id','u.id_area')
                ->where('t.estado','!=','Todos')
                ->where('u.id_area',auth()->user()->id_area)
                ->select('t.id', 't.titulo', 't.descripcion', 't.estado','a.descripcion as area','t.fecha_limite')
                ->get()
                ->groupBy('id') // agrupa por id de tarea
                ->map(function ($items) {
                    $tarea = $items->first(); // toma los datos generales de la tarea
                    return [
                        'id' => $tarea->id,
                        'titulo' => $tarea->titulo,
                        'descripcion' => $tarea->descripcion,
                        'estado' => $tarea->estado,
                        'fecha_limite' => $tarea->fecha_limite,
                        'areas' => $items->pluck('area')->unique()->values(), // crea array de áreas sin repetir
                    ];
                })
                ->values();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$tareas
            ]);

        }catch (\Throwable $e) {
            \Log::error('TareasController => buscarMisTareas => mensaje => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }
    }

    public function detalleTarea($id){
        try{
            $tareas=\DB::table('tareas as t')
                // ->leftJoin('proyectos as p', 'p.id','t.proyecto_id')
                ->leftJoin('tarea_usuario as tu', 'tu.tarea_id','t.id')
                ->leftJoin('users as u', 'u.id','tu.usuario_id')
                ->leftJoin('area as a', 'a.id','u.id_area')
                ->where('t.estado','!=','Todos')
                ->where('t.id',$id)
                ->select('t.id', 't.titulo as titulo_tarea', 't.descripcion as desc_tarea', 't.estado','a.descripcion as area','t.fecha_limite')
                ->get()
                ->groupBy('id') // agrupa por id de tarea
                ->map(function ($items) {
                    $tarea = $items->first(); // toma los datos generales de la tarea
                    return [
                        'id' => $tarea->id,
                        'titulo_tarea' => $tarea->titulo_tarea,
                        'desc_tarea' => $tarea->desc_tarea,
                        // 'nombre_proy' => $tarea->nombre_proy,
                        // 'descrpcion_proy' => $tarea->descrpcion_proy,
                        'estado' => $tarea->estado,
                        'fecha_limite' => $tarea->fecha_limite,
                        'areas' => $items->pluck('area')->unique()->values(), // crea array de áreas sin repetir
                    ];
                })
                ->values();

            $actividades=\DB::table('actividades as act')
            ->leftJoin('actividades as padre', 'act.id_padre', '=', 'padre.id')
            ->leftJoin('users as u', 'u.id','act.usuario_id')
            ->leftJoin('area as a', 'a.id','u.id_area')
            ->where('act.tarea_id',$id)
            ->select('act.created_at','a.descripcion as area_name', 'act.comentario','act.archivos','act.id','padre.comentario as descripcion_padre','padre.id as padre_id','act.usuario_id')
            ->get();

            $estadoMiTarea=\DB::table('tarea_usuario')
            ->where('tarea_id',$id)
            ->where('usuario_id',auth()->user()->id)
            ->select('estado')
            ->first();

            $estadoTareas=\DB::table('tarea_usuario as tu')
            ->leftJoin('users as u', 'u.id','tu.usuario_id')
            ->leftJoin('area as a', 'a.id','u.id_area')
            ->select('a.descripcion as area_name','tu.estado')
            ->where('tarea_id',$id)
            ->get();
                       
            return response()->json([
                'error'=>false,
                'resultado'=>$tareas,
                'actividades'=>$actividades,
                'estadoMiTarea'=>$estadoMiTarea,
                'estadoTareas'=>$estadoTareas,
                'idusuario'=>auth()->user()->id
            ]);

        }catch (\Throwable $e) {
            dd($e);
            \Log::error('TareasController => detalleTarea => mensaje => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }
    }

    public function listarActividad($id){
        try{
            
            $actividades=\DB::table('actividades as act')
            ->leftJoin('actividades as padre', 'act.id_padre', '=', 'padre.id')
            ->leftJoin('users as u', 'u.id','act.usuario_id')
            ->leftJoin('area as a', 'a.id','u.id_area')
            ->where('act.tarea_id',$id)
            ->select('act.created_at','a.descripcion as area_name', 'act.comentario','act.archivos','act.id','padre.comentario as descripcion_padre','padre.id as padre_id','act.usuario_id')
            ->get();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$actividades,
                'idusuario'=>auth()->user()->id
            ]);

        }catch (\Throwable $e) {
            \Log::error('TareasController => listarActividad => mensaje => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }
    }

    public function store1(Request $request)
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
            //Notification::send($usuario, new TareaCreada($tarea));
        }
        //Creacion de actividad inicial
        $tarea->actividades()->create([
            'usuario_id' => auth()->id(),
            'comentario' => 'Tarea creada y asignada',
            'archivos' => null,
            'tipo' => 'Comentario',
        ]);
        //$usuario = User::find($request->usuario_id);
        //$usuario->notify(new TareaCreada($tarea));
        //Notification::send($usuario, new TareaAsignada($tarea));

        return redirect()->route('home')->with('success', 'Tarea creada y asignada correctamente.');
    }

    public function store(Request $request)
    {
        try{
                      
            $guarda_tarea=new tareas();
            $guarda_tarea->titulo=$request->titulo;
            $guarda_tarea->descripcion=$request->descripcion;
            $guarda_tarea->estado=1;
            // $guarda_tarea->proyecto_id= $request->proyecto;
            $guarda_tarea->fecha_limite= $request->flimite;
            $guarda_tarea->id_usuario_reg=auth()->user()->id;      

            //validar que el proyecto no se repita
            $valida_tarea=tareas::where('titulo', $guarda_tarea->titulo)
            ->where('estado',1)
            ->first();

            if(!is_null($valida_tarea)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La tarea ya existe'
                ]);
            }

            if($guarda_tarea->save()){
                $areas=$request->areas;
                // Asignar usuarios a la tarea
                foreach ($areas as $areaId) {
                    // Realizar alguna operación con cada usuario, como asociarlo a la tarea
                    $usuario = User::where('id_area',$areaId)->first();
                    $guarda_tarea->usuarios()->attach($usuario->id);
                    // $usuario = User::find($usuarioId);
                    // $usuario->notify(new TareaCreada($tarea));
                    //Notification::send($usuario, new TareaCreada($tarea));
                }
                //Creacion de actividad inicial
                $guarda_tarea->actividades()->create([
                    'usuario_id' => auth()->id(),
                    'comentario' => 'Tarea creada y asignada',
                    'archivos' => null,
                    'tipo' => 'Comentario',
                ]);
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Información registrada exitosamente'
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se pudo registrar la información'
                ]);
            }

        }catch (\Throwable $e) {
            \Log::error('TareasController => store => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }

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

    public function agregarActividad_(Request $request, tareas $tarea) {
        $request->validate([
            'comentario' => 'nullable|string',
            'archivos.*' => 'nullable|file|max:2048'
        ]);

        $archivosPaths = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $archivosPaths[] = $archivo->store('archivos', 'public');
            }
        }

        $tarea->actividades()->create([
            'usuario_id' => auth()->id(),
            'comentario' => $request->comentario,
            //'archivos' => count($archivosPaths) ? $archivosPaths : null,
            'archivos' => count($archivosPaths) ? json_encode($archivosPaths) : json_encode([]),
            'tipo' => $request->comentario ? 'Comentario' : 'Archivo',
        ]);

        return back()->with('success', 'Actividad añadida.');
    }

    public function agregarActividad(Request $request) {
        try{
            $verificaEstado=TareaUsuario::where('tarea_id',$request->id_tarea_act)
            ->where('usuario_id',auth()->user()->id)
            ->where('estado','Completado')
            ->first();
           
            if(!is_null($verificaEstado)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ya no se puede agregar mas actividad, la tarea ya fue finalizada'
                ]);    
            }

            $request->validate([
                'comentario' => 'nullable|string',
                'archivos.*' => 'nullable|file|max:2048'
            ]);

            $archivosInfo = [];
            $ruta = public_path('archivos'); // public/uploads
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                  
                    $nombreOriginal = $archivo->getClientOriginalName();
                    $nombreSinExtension = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                    
                    $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
                    $nombreLimpio=$nombreSinExtension."".time().".".$extension;
                   
                    Storage::disk('public')->putFileAs('archivos', $archivo, $nombreLimpio);

                    $archivosInfo[] = [
                        'nombre' => $nombreLimpio
                    ];
                }
            }
           

            $tarea = tareas::find($request->id_tarea_act);
            $tarea->estado='En Proceso';
            $tarea->save();

            $tarea->actividades()->create([
                'usuario_id' => auth()->id(),
                'comentario' => $request->comentario,        
                'archivos' => json_encode($archivosInfo),
                'tipo' => $request->comentario ? 'Comentario' : 'Archivo',
            ]);

            $actualizaEstado=TareaUsuario::where('tarea_id',$request->id_tarea_act)
            ->where('usuario_id',auth()->user()->id)
            ->update(['estado'=>'Atendido']);

            $verificaEstadoTareaAct=$this->verificaEstadoTarea($request->id_tarea_act);
            if($verificaEstadoTareaAct['error']==true){
                return response()->json([
                    'error' => true,
                    'mensaje' => $verificaEstadoTareaAct['mensaje']
                ]);
            }

            return response()->json([
                'error' => false,
                'mensaje' => 'Actividad añadida.'
            ]);


        }catch (\Throwable $e) {
            \Log::error('TareasController => agregarActividad => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }

    }

    public function agregarObservacion(Request $request) {
        try{
            $verificaEstado=TareaUsuario::where('tarea_id',$request->id_tarea_act)
            ->where('usuario_id',auth()->user()->id)
            ->where('estado','Completado')
            ->first();
           
            if(!is_null($verificaEstado)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ya no se puede agregar mas comentario, la tarea ya fue finalizada'
                ]);    
            }

            $request->validate([
                'observacion' => 'nullable|string',
                'archivos_observacion.*' => 'nullable|file|max:2048'
            ]);

            $archivosInfo = [];
            $ruta = public_path('archivos_observacion'); // public/uploads
            if ($request->hasFile('archivos_observacion')) {
                foreach ($request->file('archivos_observacion') as $archivo) {
                  
                    $nombreOriginal = $archivo->getClientOriginalName();
                    $nombreSinExtension = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                    
                    $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
                    $nombreLimpio=$nombreSinExtension."".time().".".$extension;
                   
                    Storage::disk('public')->putFileAs('archivos', $archivo, $nombreLimpio);

                    $archivosInfo[] = [
                        'nombre' => $nombreLimpio
                    ];
                }
            }           

            $tarea = tareas::find($request->id_tarea_act); 
            // dd($tarea->actividades()->get());         

            $tarea->actividades()->create([
                'usuario_id' => auth()->id(),
                'comentario' => $request->observacion,        
                'archivos' => json_encode($archivosInfo),
                'tipo' => 'Observacion',
                'id_padre' => $request->idactividad_observacion
            ]);

            return response()->json([
                'error' => false,
                'mensaje' => 'Observacion añadida exitosamente.'
            ]);


        }catch (\Throwable $e) {
            \Log::error('TareasController => agregarObservacion => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ]);        
        }

    }

    public function verificaEstadoTarea($id){
        try{
           
            $comprobar = TareaUsuario::where('tarea_id', $id)
            ->where(function($q) {
                $q->where('estado', '<>', 'Eliminado')
                ->orWhereNull('estado');
            })
            ->get();
            
            $verifica=TareaUsuario::where('tarea_id',$id)
            ->where('estado','=','Completado')
            ->get();
           
            if(sizeof($comprobar)==sizeof($verifica)){
                $completaTarea=tareas::where('id',$id)
                ->update(['estado'=>'Completada']);
            }

            return[
                'error'=>false,
                'mensaje'=>'OK'
            ];

        }catch (\Throwable $e) {
            \Log::error('TareasController => verificaEstadoTarea => mensaje => '.$e->getMessage());
            return[
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ];        
        }
    }
    
     public function finalizarTarea($id){
        try{

            $verificaEstado=TareaUsuario::where('tarea_id',$id)
            ->where('usuario_id',auth()->user()->id)
            ->where('estado','Completado')
            ->first();
            if(!is_null($verificaEstado)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La tarea ya fue finalizada'
                ]);    
            }

           
            $actualizaEstado=TareaUsuario::where('tarea_id',$id)
            ->where('usuario_id',auth()->user()->id)
            ->update(['estado'=>'Completado']);

            $verificaEstadoTareaAct=$this->verificaEstadoTarea($id);
           
            if($verificaEstadoTareaAct['error']==true){
                return response()->json([
                    'error' => true,
                    'mensaje' => $verificaEstadoTareaAct['mensaje']
                ]);
            }

            return response()->json([
                'error' => false,
                'mensaje' => 'Tarea Completada Exitosamente'
            ]);

        }catch (\Throwable $e) {
            \Log::error('TareasController => verificaEstadoTarea => mensaje => '.$e->getMessage());
            return[
                'error'=>true,
                'mensaje'=>'Ocurrió un error '.$e
            ];        
        }
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

    public function descargarArchivo($id, $archivoIndex = 0)
    {
        $actividad = actividad::find($id);
       

        // Verificar que la actividad exista y tenga un archivo
        if (!$actividad || !$actividad->archivos) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Decodificar la lista de archivos de la actividad
        $archivos = json_decode($actividad->archivos, true);
         
        
        // if (!Storage::disk('public')->exists($archivos[$archivoIndex]['nombre'])) {
        //     return response()->json(['error' => 'Archivo no encontrado'], 404);
        // }
     

        // $path = $archivos[$archivoIndex];

        // // Obtener la extensión real del archivo
        // $extension = pathinfo($path, PATHINFO_EXTENSION);

        // // Nombre limpio para la descarga
        // $slug = Str::slug(pathinfo($path, PATHINFO_FILENAME)) . '.' . $extension;

        // return Storage::disk('public')->download($path, $slug);

         $archivo = $archivos[$archivoIndex];

        // Construir ruta física completa en public/archivos
        // $ruta = public_path('storage/archivos/' . $archivo['nombre']);
        $ruta = public_path('storage/archivos/' . $archivo['nombre']);
        
       

        // Verificar existencia del archivo
        if (!file_exists($ruta)) {
            return response()->json(['error' => 'Archivo no encontrado en el servidor'], 404);
        }

        // Generar nombre limpio para descarga
        $nombreDescarga = Str::slug($archivo['nombre']);
        // dd($nombreDescarga);
        $nombreDescarga="";

        // Descargar el archivo directamente desde public
        return response()->download($ruta, $nombreDescarga);
    }


}
