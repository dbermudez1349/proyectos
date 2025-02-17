@extends('layouts.app')

@section('content')
<div class="container py-4">

    <!-- Detalle de la Tarea -->
    <fieldset class="border rounded p-3 mb-4">
        <legend class="w-auto fw-bold">
            <i class="bi bi-list-task"></i> Detalle de la Tarea
        </legend>
        <p><i class="bi bi-card-text"></i> <strong>Título:</strong> {{ $tarea->titulo }}</p>
        <p><i class="bi bi-file-text"></i> <strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
        <p><i class="bi bi-flag"></i> <strong>Estado:</strong>
            <span class="badge bg-{{ $tarea->estado == 'Completada' ? 'success' : 'warning' }}">
                {{ $tarea->estado }}
            </span>
        </p>
        <p><i class="bi bi-people"></i> <strong>Usuarios Asignados:</strong>
            <span class="badge bg-primary">{{ $tarea->usuarios->pluck('name')->join(' | ') }}</span>
        </p>
    </fieldset>

    <!-- Actividades -->
    <fieldset class="border rounded p-3 mb-4">
        <legend class="w-auto text-dark fw-bold me-3">
            <i class="bi bi-chat-left-text"></i> Actividades
        </legend>
        @if ($tarea->estado == 'Pendiente' || $tarea->estado == 'Atrasada')
        <!-- Botón para abrir modal de Añadir Actividad -->
            @can('añadir actividades')
                <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#actividadModal">
                    <i class="bi bi-plus-circle"></i> Añadir Actividad
                </button>
            @endcan
        @endif
        <div class="chat-box" style="max-height: 400px; overflow-y: auto;">
            @forelse($tarea->actividades as $actividad)
                <div class="d-flex align-items-start mb-3">
                    <div class="me-2">
                        <i class="bi bi-person-circle text-secondary fs-4"></i>
                    </div>
                    <div class="p-3 rounded border bg-white w-100">
                        <strong>{{ $actividad->usuario->name }}</strong>
                        <small class="text-muted d-block">{{ $actividad->created_at->diffForHumans() }}</small>
                        <p class="mb-1">{{ $actividad->comentario }}</p>
                        @if($actividad->archivos)
                            <p class="mb-0"><strong><i class="bi bi-paperclip"></i> Archivos adjuntos:</strong></p>
                            <ul class="list-unstyled">
                                @foreach($actividad->archivos as $archivo)
                                    <li>
                                        <a href="{{ Storage::url($archivo) }}" target="_blank" class="text-primary">
                                            <i class="bi bi-file-earmark"></i> Ver archivo
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No hay actividades registradas aún.</p>
            @endforelse
        </div>
    </fieldset>
    @if ($tarea->estado == 'Pendiente' || $tarea->estado == 'Atrasada')
        <!-- Botón para completar tarea -->
        @can('completar tareas')
            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#confirmModal">
                <i class="bi bi-check-circle"></i> Completar Tarea
            </button>
        @endcan
    @endif

    @can('completar tareas')
    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel"><i class="bi bi-exclamation-triangle"></i> Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de marcar la tarea como completada? Esta acción se registrará.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('tareas.completar', $tarea->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Sí, completar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @can('añadir actividades')
    <!-- Modal para añadir actividad -->
    <div class="modal fade" id="actividadModal" tabindex="-1" aria-labelledby="actividadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actividadModalLabel"><i class="bi bi-pencil-square"></i> Añadir Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tareas.agregarActividad', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comentario" class="form-label"><i class="bi bi-chat-dots"></i> Comentario</label>
                            <textarea class="form-control" name="comentario" rows="3" placeholder="Escribe un comentario..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="archivos" class="form-label"><i class="bi bi-paperclip"></i> Adjuntar Archivos</label>
                            <input type="file" class="form-control" name="archivos[]" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Añadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
