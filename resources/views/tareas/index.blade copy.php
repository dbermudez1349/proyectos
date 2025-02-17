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
    <div id="taskView">
        @include("tareas.views.$view")
    </div>
</div>

<script>
document.getElementById("viewSelector").addEventListener("change", function() {
    let view = this.value;
    window.location.href = `{{ route('tareas.index') }}?view=${view}`;
});
</script>
@endsection
