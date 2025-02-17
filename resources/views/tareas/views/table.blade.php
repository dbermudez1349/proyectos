@push('styles')
<link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/rowReorder.bootstrap5.min.css') }}" rel="stylesheet">
@endpush
<table class="table table-striped">
    <thead>
        <tr>
            <th>Título</th>
            <th>Proyecto</th>
            <th>Usuarios</th>
            <th>Estado</th>
            <th>Fecha Límite</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tareas as $tarea)
            <tr>
                <td>{{ $tarea->titulo }}</td>
                <td>{{ $tarea->proyecto->nombre ?? 'Sin asignar' }}</td>
                <td>{{ $tarea->usuarios->pluck('name')->join(', ') }}</td>
                <td>
                    <span class="badge bg-{{ $tarea->estado == 'completada' ? 'success' : ($tarea->estado == 'en progreso' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($tarea->estado) }}
                    </span>
                </td>
                <td>{{ $tarea->fecha_limite ?? '-' }}</td>
                <td>
                    <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
 <!-- jQuery -->
<script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
<!-- DataTables -->

<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/dataTables.rowReorder.min.js') }}"></script>
<script>

    $(document).ready(function(){
        ttareas = $("#tabla-tareas").DataTable({
            "lengthMenu": [ 5, 10],
            "language" : {
                "url": '{{ asset("/js/spanish.json") }}',
            },
            "autoWidth": true,
            "rowReorder": false,
            "order": [], //Initial no order
            "processing" : false,
            "serverSide": false,

            //"columnDefs": [{ targets: [3], "orderable": false}],

            "fixedColumns" : true
        });
    });
</script>
@endpush
