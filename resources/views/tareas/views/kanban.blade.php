<div class="row">
    @foreach(['Pendiente', 'Atrasada', 'Completada'] as $estado)
        <div class="col-md-4">
            <h5 class="text-center">{{ ucfirst($estado) }}</h5>
            <div class="border p-2 bg-light rounded" style="min-height: 200px;">
                @foreach($tareas->where('estado', $estado) as $tarea)
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $tarea->titulo }}</h5>
                            <small>Proyecto: {{ $tarea->proyecto->nombre ?? 'Sin asignar' }}</small><br>
                            <small class="text-muted">Asignado a: {{ $tarea->usuarios->pluck('name')->join(', ') }}</small><br>
                            <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-primary btn-sm">Ver</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
