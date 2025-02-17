<div class="row">
    <!-- Tareas Pendientes -->
    <div class="col-md-4">
        <h4 class="text-center">Pendientes</h4>
        <div class="card bg-light p-2">
            @foreach($tareas->where('estado', 'Pendiente') as $tarea)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $tarea->titulo }}</h5>
                        <p class="text-muted">Asignado a: {{$tarea->usuarios->pluck('name')->join(' | ')}}</p>
                        <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tareas Retrasadas -->
    <div class="col-md-4">
        <h4 class="text-center">Retrasadas</h4>
        <div class="card bg-light p-2">
            @foreach($tareas->where('estado', 'Atrasada') as $tarea)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $tarea->titulo }}</h5>

                        <p class="text-muted">Asignado a: {{ $tarea->usuario->name }}</p>
                        <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-warning btn-sm">Ver</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tareas Completadas -->
    <div class="col-md-4">
        <h4 class="text-center">Completadas</h4>
        <div class="card bg-light p-2">
            @foreach($tareas->where('estado', 'Completada') as $tarea)
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $tarea->titulo }}</h5>
                        <p class="text-muted">Asignado a: {{ $tarea->usuarios->pluck('name')->join(' | ') }}</p>
                        <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
