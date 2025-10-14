<?php

namespace App\Http\Controllers;

use App\Models\proyectos;
use Illuminate\Http\Request;

class ProyectosController extends Controller
{
    public function index()
    {
       if(!Auth()->user()->hasPermissionTo('ver proyectos'))
        {
            abort(403, 'No tienes acceso a esta seccion.');
        }
        return view('proyectos.index');
    }

    public function listar(){
        try{
            $proyectos=Proyectos::where('estado','!=','0')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$proyectos
            ]);
        }catch (\Throwable $e) {
            \Log::error('ProyectosController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function show($id){
        try{
            $proyectos=Proyectos::where('id',$id)->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$proyectos
            ]);
        }catch (\Throwable $e) {
            \Log::error('ProyectosController => show => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    public function create()
    {
        return view('proyectos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Proyectos::create($request->all());
        // return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');

        $guarda_proyecto=new Proyectos();
        $guarda_proyecto->nombre=$request->nombre;
        $guarda_proyecto->descripcion=$request->descripcion;
        $guarda_proyecto->estado=1;
        $guarda_proyecto->id_usuario_reg=auth()->user()->id;      

        //validar que el proyecto no se repita
        $valida_proyecto=Proyectos::where('nombre', $guarda_proyecto->nombre)
        ->where('estado',1)
        ->first();

        if(!is_null($valida_proyecto)){
            return response()->json([
                'error'=>true,
                'mensaje'=>'El proyecto ya existe'
            ]);
        }

        if($guarda_proyecto->save()){
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
    }

    public function edit(Proyectos $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // $proyecto->update($request->all());
        // return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado.');

        $actualiza_proyecto=Proyectos::find($id);
        $actualiza_proyecto->nombre=$request->nombre;
        $actualiza_proyecto->descripcion=$request->descripcion;
        $actualiza_proyecto->estado=1;
        $actualiza_proyecto->id_usuario_act=auth()->user()->id;      

        //validar que el proyecto no se repita
        $valida_proyecto=Proyectos::where('nombre', $actualiza_proyecto->nombre)
        ->where('estado',1)
        ->where('id','!=',$id)
        ->first();

        if(!is_null($valida_proyecto)){
            return response()->json([
                'error'=>true,
                'mensaje'=>'El proyecto ya existe'
            ]);
        }

        //comprobar que no tenga tareas en fase iniciada o completadas
        $verifica=\DB::table('tareas')
        ->where('proyecto_id',$id)
        ->whereIn('estado',['Completada','En Proceso'])
        ->first();
        if(!is_null($verifica)){
            return response()->json([
                'error'=>true,
                'mensaje'=>'El proyecto no se puede actualizar porque ya esta asociado a una tarea en ejecucion o finalizado'
            ]);
        }

        if($actualiza_proyecto->save()){
            return response()->json([
                'error'=>false,
                'mensaje'=>'Información actualizada exitosamente'
            ]);
        }else{
            return response()->json([
                'error'=>true,
                'mensaje'=>'No se pudo actualizar la información'
            ]);
        }
    }

    public function destroy($id)
    {
        //comprobar que no tenga tareas en fase iniciada o completadas
        $tieneTareaActiva=\DB::table('tareas')
        ->where('proyecto_id',$id)
        ->whereIn('estado',['Completada','En Proceso'])
        ->first();
       
        if(!is_null($tieneTareaActiva)){
            return response()->json([
                'error'=>true,
                'mensaje'=>'El proyecto no se puede eliminar porque tiene tarea(s) asociada(s) en ejecucion o finalizado'
            ]);
        }

        $elimina_proyecto=Proyectos::find($id);
        $elimina_proyecto->estado=0;
        $elimina_proyecto->id_usuario_elimina=auth()->user()->id; 
        $elimina_proyecto->save(); 

         return response()->json([
            'error'=>false,
            'mensaje'=>'Información eliminada exitosamente'
        ]);


    }

    public function buscarProyectos(Request $request){
       
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $text=mb_strtoupper($search);
           
            $data = \DB::table('proyectos')
            ->where(function ($c) use ($text) {
                $c->where('nombre', 'like', '%' . $text . '%');
            })
            ->select(\DB::raw("CONCAT(nombre, ' - ', descripcion) AS detalle"),'id')
            ->take(50)
            ->get();
        }        

        return response()->json($data);
    }
}
