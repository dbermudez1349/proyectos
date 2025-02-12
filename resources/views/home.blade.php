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
            <h3>Bandeja de Tareas 2</h3>
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
                        <th>Acciones5</th>
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
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(auth()->user()->id === $tarea->usuario_id && $tarea->estado !== 'Completada')
                                            <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        @endif

                                        @if($tarea->estado === 'Completada')
                                            <button class="btn btn-info btn-sm ver-detalle"
                                                    data-id="{{ $tarea->id }}"
                                                    data-nombre="{{ $tarea->titulo }}"
                                                    data-estado="{{ $tarea->estado }}"
                                                    data-proyecto="{{ $tarea->proyecto->nombre }}"
                                                    data-usuario="{{ $tarea->usuario->name }}"
                                                    data-descripcion="{{ $tarea->descripcion }}"
                                                    data-archivo="{{ $tarea->archivo }}">
                                                    <i class="bi bi-eye"></i> <!-- Ícono de ver -->
                                            </button>
                                            <form action="{{ route('tareas.archivar', $tarea->id) }}" method="POST" class="" onsubmit="return confirm('¿Estás seguro de archivar esta tarea?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-archive"></i></button>
                                            </form>
                                        @endif
                                    </div>
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
<!-- Modal para ver detalles de la tarea -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Detalles de la Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Proyecto:</strong> <span id="detalle-proyecto"></span></p>
                <p><strong>ID:</strong> <span id="detalle-id"></span></p>
                <p><strong>Tarea:</strong> <span id="detalle-nombre"></span></p>
                <p><strong>Descripción:</strong></p>
                <p id="detalle-descripcion"></p>
                <p><strong>Estado:</strong> <span id="detalle-estado"></span></p>
                <p><strong>Usuario asignado:</strong> <span id="detalle-usuario"></span></p>
                <p><strong>Archivo adjunto:</strong></p>
                <a id="detalle-archivo" href="#" target="_blank">Ver archivo</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".ver-detalle").forEach(button => {
            button.addEventListener("click", function() {
                let id = this.getAttribute("data-id");
                let nombre = this.getAttribute("data-nombre");
                let descripcion = this.getAttribute("data-descripcion");
                let archivo = this.getAttribute("data-archivo");
                let estado = this.getAttribute("data-estado");
                let proyecto = this.getAttribute("data-proyecto");
                let usuario = this.getAttribute("data-usuario");

                document.getElementById("detalle-proyecto").innerText = proyecto;
                document.getElementById("detalle-id").innerText = id;
                document.getElementById("detalle-nombre").innerText = nombre;
                document.getElementById("detalle-descripcion").innerText = descripcion;
                document.getElementById("detalle-estado").innerText = estado;
                document.getElementById("detalle-usuario").innerText = usuario;

                let archivoLink = document.getElementById("detalle-archivo");
                if (archivo) {
                    archivoLink.href = "/storage/" + archivo; // Ruta de Laravel para archivos
                    archivoLink.style.display = "block";
                } else {
                    archivoLink.style.display = "none";
                }

                var modal = new bootstrap.Modal(document.getElementById("modalDetalle"));
                modal.show();
            });
        });
    });
</script>
@endpush
