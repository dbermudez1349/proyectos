@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Detalle de la Tarea</h2>
    <p><strong>Título:</strong> {{ $tarea->titulo }}</p>
    <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
    <p><strong>Estado:</strong> {{ $tarea->estado }}</p>

    <form action="{{ route('tareas.completar', $tarea->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="comentario" class="form-label">Comentario</label>
            <textarea class="form-control" name="comentario" required></textarea>
        </div>
        <div class="mb-3">
            <label for="archivos" class="form-label">Adjuntar Archivos</label>
            <input type="file" class="form-control" name="archivos[]" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Completar Tarea</button>
    </form>
</div>
@endsection
