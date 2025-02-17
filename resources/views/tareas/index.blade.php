@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Tablero de Tareas</h2>
        <select id="viewSelector" class="form-select w-auto">
            <option value="kanban" {{ $view == 'kanban' ? 'selected' : '' }}>Kanban</option>
            <option value="table" {{ $view == 'table' ? 'selected' : '' }}>Tabla</option>
            <option value="calendar" {{ $view == 'calendar' ? 'selected' : '' }}>Calendario</option>
            <option value="timeline" {{ $view == 'timeline' ? 'selected' : '' }}>Línea de Tiempo</option>
        </select>
    </div>
    <form action="{{ route('tareas.index') }}" method="GET" class="row g-2 mb-3">
        {{-- Mantener la vista seleccionada --}}
        <input type="hidden" name="view" value="{{ request('view', 'kanban') }}">

        {{-- Filtro por usuario --}}
        <div class="col-md-3">
            <label for="usuario" class="form-label">Usuario</label>
            <select name="usuario" id="usuario" class="form-select">
                <option value="">Todos</option>
                @foreach($usuarios as $user)
                    <option value="{{ $user->id }}" {{ request('usuario') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filtro por proyecto --}}
        <div class="col-md-3">
            <label for="proyecto" class="form-label">Proyecto</label>
            <select name="proyecto" id="proyecto" class="form-select">
                <option value="">Todos</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id }}" {{ request('proyecto') == $proyecto->id ? 'selected' : '' }}>
                        {{ $proyecto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filtro por fecha límite --}}
        <div class="col-md-3">
            <label for="fecha" class="form-label">Fecha límite</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
        </div>

        {{-- Filtro por estado (solo en Tabla y Kanban) --}}
        @if(in_array(request('view', 'kanban'), ['kanban', 'table']))
        <div class="col-md-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en progreso" {{ request('estado') == 'en progreso' ? 'selected' : '' }}>En progreso</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
            </select>
        </div>
        @endif

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filtrar</button>
        </div>
    </form>

    <div id="taskView">
        @include("tareas.views.$view")
    </div>
</div>

<script>
    document.getElementById("viewSelector").addEventListener("change", function() {
        let view = this.value;
        let params = new URLSearchParams(window.location.search);
        params.set('view', view);
        window.location.href = `{{ route('tareas.index') }}?${params.toString()}`;
    });
    </script>
@endsection
