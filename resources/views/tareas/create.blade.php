@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Crear Nueva Tarea</h2>
    <form action="{{ route('tareas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
        </div>
        <div class="mb-3">
            <label for="proyecto_id" class="form-label">Proyecto</label>
            <select class="form-control" id="proyecto_id" name="proyecto_id" required>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="usuario_id" class="form-label">Asignar a</label>
            <select class="form-control" id="usuario_id" name="usuario_id" required>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_limite" class="form-label">Fecha Límite</label>
            <input type="date" class="form-control" id="fecha_limite" name="fecha_limite">
        </div>
        <div class="mb-3">
            <label for="archivo" class="form-label">Adjuntar Archivo</label>
            <input type="file" class="form-control" id="archivo" name="archivo">
        </div>
        <button type="submit" class="btn btn-primary">Guardar Tarea</button>
    </form>
</div>
@endsection
