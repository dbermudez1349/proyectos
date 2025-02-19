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
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{session('success')}}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{session('error')}}
                </div>
            @endif
        </div>
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('proyectos.create') }}" class="list-group-item list-group-item-action">Crear Proyecto</a>
                <a href="{{ route('tareas.create') }}" class="list-group-item list-group-item-action">Crear Tarea</a>
            </div>
        </div>
        <div class="col-md-9">
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
                        <th>Asignado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tareas as $tarea)
                        @if(!$tarea->archivar)
                            <tr>
                                <td>{{ $tarea->titulo }}</td>
                                @if ($tarea->estado == "Completada")
                                    <td><span class="badge bg-success">{{ $tarea->estado }}</span></td>
                                @elseif ($tarea->estado == "Pendiente")
                                    <td><span class="badge bg-warning">{{ $tarea->estado }}</span></td>
                                @elseif ($tarea->estado == "Retrasada")
                                    <td><span class="badge bg-danger">{{ $tarea->estado }}</span></td>
                                @else
                                    <td><span class="badge bg-danger">{{ $tarea->estado }}</span></td>
                                @endif

                                <td>{{ $tarea->proyecto->nombre ?? 'Sin Proyecto' }}</td>
                                <td>{{ $tarea->usuario->name }}</td>
                                <td>
                                    @if(auth()->user()->id === $tarea->usuario_id && $tarea->estado !== 'Completada')
                                        <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif

                                    @if($tarea->estado === 'Completada')
                                        <form action="{{ route('tareas.archivar', $tarea->id) }}" method="POST" class="" onsubmit="return confirm('¿Estás seguro de archivar esta tarea?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-archive"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
