<ul class="timeline">
    @foreach($tareas as $tarea)
        <li>
            <strong>{{ $tarea->titulo }}</strong> - {{ $tarea->estado }}
            <span class="text-muted">{{ $tarea->created_at->diffForHumans() }}</span>
        </li>
    @endforeach
</ul>

