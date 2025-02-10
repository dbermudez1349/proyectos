@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Bienvenido al Gestor de Tareas</h2>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestor de Tareas</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">{{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Cerrar Sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="list-group">
                <a href="{{ route('proyectos.create') }}" class="list-group-item list-group-item-action">Crear Proyecto</a>
                <a href="{{ route('tareas.create') }}" class="list-group-item list-group-item-action">Crear Tarea</a>
            </div>
        </div>
        <div class="col-md-8">
            <h3>Bandeja de Tareas</h3>
            @if($tareas->isEmpty())
                <div class="alert alert-info">No hay tareas registradas.</div>
            @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Estado</th>
                        <th>Proyecto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tareas as $tarea)
                    <tr>
                        <td>{{ $tarea->titulo }}</td>
                        <td>{{ $tarea->estado }}</td>
                        <td>{{ $tarea->proyecto->nombre ?? 'Sin Proyecto' }}</td>
                        <td>
                            @if(Auth::id() == $tarea->usuario_id && $tarea->estado != 'Completada')
                                <a href="{{ route('tareas.realizar', $tarea->id) }}" class="btn btn-primary">Realizar Tarea</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
