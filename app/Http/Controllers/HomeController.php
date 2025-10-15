<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tareas;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tareas = auth()->user()->tareas()->get();
        // Datos simulados de ejemplo
        $years = ['2011', '2012', '2013'];
        $salesA = [6500, 8000, 23000];
        $salesB = [4000, 6000, 17000];


         // Datos para el gráfico de barras
        $categories = ['Enero', 'Febrero', 'Marzo', 'Abril'];
        $barValues = [12000, 19000, 3000, 5000];

        return view('home', compact('tareas','years', 'salesA', 'salesB', 'categories', 'barValues'));
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
