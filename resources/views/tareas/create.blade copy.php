@extends('layouts.app')
@push('styles')
<style>
    .multi-select-container {
        position: relative;
        width: 100%;
    }
    .dropdown-menu {
        width: 100%;
        max-height: 250px;
        overflow-y: auto;
    }
    .multi-select-labels {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 5px;
    }
    .multi-select-label {
        background: #007bff;
        color: white;
        padding: 3px 8px;
        border-radius: 3px;
        display: flex;
        align-items: center;
    }
    .multi-select-label span {
        cursor: pointer;
        margin-left: 5px;
        font-weight: bold;
    }
</style>
@endpush
@section('content')
<div class="container">
    <h2>Crear Nueva Tarea</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tareas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>


        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
        </div>
        <div class="mb-3">
            <label for="usuarios" class="form-label">Asignar a Usuarios</label>
            <select class="form-select" id="usuarios" name="usuarios2[]" multiple required>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Selecciona uno o más usuarios.</small>
        </div>

        <!-- Contenedor de las etiquetas de los usuarios seleccionados -->
        <div class="multi-select-labels" id="selectedLabels"></div>
        <br>

        <div class="mb-3">
            <label for="proyecto_id" class="form-label">Proyecto</label>
            <select class="form-control" id="proyecto_id" name="proyecto_id" required>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id }}">{{ $proyecto->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_limite" class="form-label">Fecha Límite</label>
            <input type="date" class="form-control" id="fecha_limite" name="fecha_limite">
        </div>
        <button type="submit" class="btn btn-primary">Crear Tarea</button>
    </form>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const usuariosSelect = document.getElementById("usuarios");
    const selectedLabels = document.getElementById("selectedLabels");
    const placeholder = document.getElementById("placeholder");

    // Inicializar etiquetas de los usuarios seleccionados
    function updateSelectedLabels() {
        selectedLabels.innerHTML = "";
        const selectedOptions = Array.from(usuariosSelect.selectedOptions);
        selectedOptions.forEach(option => {
            const label = document.createElement("div");
            label.classList.add("multi-select-label");
            label.innerHTML = `${option.innerText} <span data-id="${option.value}">&times;</span>`;
            label.querySelector("span").addEventListener("click", function() {
                // Eliminar la opción seleccionada y desmarcarla en el select
                option.selected = false;
                updateSelectedLabels();  // Actualizar las etiquetas
            });
            selectedLabels.appendChild(label);
        });

        // Actualizar el placeholder
        placeholder.style.display = selectedOptions.length ? "none" : "inline";
    }

    // Añadir evento para el cambio en el select
    usuariosSelect.addEventListener("change", function() {
        updateSelectedLabels();
    });

    // Inicializar las etiquetas al cargar la página (por si ya hay opciones seleccionadas)
    updateSelectedLabels();
});


</script>
@endpush
