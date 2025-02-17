@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-archive"></i> Tareas Archivadas</h2>

    <!-- Barra de búsqueda -->
    <div class="mb-3">
        <input type="text" class="form-control" id="buscarTarea" placeholder="Buscar tarea..." onkeyup="filtrarTareas()">
    </div>

    <!-- Tabla de tareas archivadas -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Usuarios Asignados</th>
                    <th>Fecha de Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="listaTareas">
                @foreach($tareasArchivadas as $tarea)
                    <tr>
                        <td>{{ $tarea->id }}</td>
                        <td>{{ $tarea->titulo }}</td>
                        <td>{{ Str::limit($tarea->descripcion, 50) }}</td>
                        <td>{{ $tarea->usuarios->pluck('name')->join(', ') }}</td>
                        <td>{{ $tarea->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('tareas.restaurar', $tarea->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function filtrarTareas() {
    let input = document.getElementById("buscarTarea").value.toLowerCase();
    let rows = document.querySelectorAll("#listaTareas tr");

    rows.forEach(row => {
        let title = row.children[1].innerText.toLowerCase();
        row.style.display = title.includes(input) ? "" : "none";
    });
}
</script>
@endsection
