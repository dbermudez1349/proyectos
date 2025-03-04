@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" />
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
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Tarea de ejemplo" required>
        </div>


        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion de tarea de ejemplo"></textarea>
        </div>
        <div class="mb-3">
            <label for="usuarios" class="form-label">Asignar a Usuarios</label>
            <select class="form-select" id="usuarios" name="usuarios2[]" data-placeholder="seleccione usuarios" multiple>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

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
            <input type="datetime-local" class="form-control" id="fecha_limite" name="fecha_limite">
        </div>
        <br>
        <button type="submit" class="btn btn-primary mb-5">Crear Tarea</button>
    </form>
</div>
@endsection
@push('scripts')
 <!-- jQuery -->
 <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
<script>
    $(document).ready(function(){
        $( '#usuarios' ).select2( {
            theme: "bootstrap-5",
            width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
    })

</script>
<script src="{{ asset('js/select2.min.js') }}"></script>
@endpush
