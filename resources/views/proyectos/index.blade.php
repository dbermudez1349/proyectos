@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Lista de Proyectos</h2>
    <a href="{{ route('proyectos.create') }}" class="btn btn-primary mb-3">Crear Proyecto</a>
    @if($proyectos->isEmpty())
        <div class="alert alert-info">No hay proyectos registrados.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
            <tr>
                <td>{{ $proyecto->nombre }}</td>
                <td>{{ $proyecto->descripcion }}</td>
                <td>
                    <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
