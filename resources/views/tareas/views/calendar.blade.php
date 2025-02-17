<div id='calendar'></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
            @foreach($tareas as $tarea)
                {
                    title: "{{ $tarea->titulo }}",
                    start: "{{ $tarea->created_at->format('Y-m-d') }}"
                },
            @endforeach
        ]
    });
    calendar.render();
});
</script>
