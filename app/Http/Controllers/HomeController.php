<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tareas;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tareas = auth()->user()->tareas()->get();
       
        // Obtener la fecha límite (6 meses atrás desde hoy)
        $fechaInicio = Carbon::now()->subMonths(6);

        // Consultar las tareas agrupadas por mes y contar cuántas hay por cada uno
        $tareas = \DB::table('tareas')
            ->selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->where('created_at', '>=', $fechaInicio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Transformar los resultados en arreglos para usar en el gráfico
        $meses = [];
        $totales = [];

        foreach ($tareas as $t) {
            // Formatear el nombre del mes
            $meses[] = Carbon::create()->month($t->mes)->format('F');
            $totales[] = $t->total;
        }

        $labels = $meses;   // ej. ['Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre']
        $data = $totales;

       
        $tareasPorEstado = \DB::table('tareas')
        ->select('estado', \DB::raw('COUNT(*) as total'))
        ->groupBy('estado')
        ->get();

        // Convertimos a arrays para pasar a Chart.js
        $categories = $tareasPorEstado->pluck('estado');
        $barValues = $tareasPorEstado->pluck('total');

        $tareasTotales=\DB::table('tareas')
        ->get();

        $misTareasPendientesTotales=\DB::table('tarea_usuario')
        ->where('usuario_id', auth()->user()->id)
        ->where(function($q) {
            $q->where('estado', '<>', 'Eliminado')
            ->orWhereNull('estado');
        })
        ->get();

        $proyectosTotales=\DB::table('proyectos')
        ->where('estado',1)
        ->get();

        $usuarioTotales=\DB::table('users')
        ->where('estado',1)
        ->get();


        return view('home', compact('labels','data', 'categories', 'barValues','tareasTotales',
        'proyectosTotales','usuarioTotales','misTareasPendientesTotales'));
    }

    public function misTareas()
    {
        $tareas = auth()->user()->tareas()->get();
        return view('misTareas', compact('tareas'));
    }


    public function tablero()
    {
        $tareas = tareas::with('proyecto')->orderBy('estado')->get();
        return view('tablero', compact('tareas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
