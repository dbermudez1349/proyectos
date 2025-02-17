@push('styles')
<link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/rowReorder.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

<table class="table table-bordered" id="tabla-tareas">
    <thead>
        <tr>
            <th>Título</th>
            <th>Estado</th>
            <th>Proyecto</th>
            <th>Usuarios asignados</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tareas as $tarea)
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
                <td>{{ $tarea->estado }}</td>
                <td>{{ $tarea->usuarios->pluck('name')->join(', ') }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        @if(auth()->user()->id && $tarea->estado !== 'Completada')
                            <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('tareas.archivar', $tarea->id) }}" method="POST" class="" onsubmit="return confirm('¿Estás seguro de archivar esta tarea?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-archive"></i></button>
                            </form>
                        @endif

                        @if($tarea->estado === 'Completada')
                            <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        @endif
                    </div>
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
