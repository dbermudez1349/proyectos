<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <!-- Styles -->
    <link href="{{ asset('css/bootstrap53.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    @stack('styles')
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap53.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}" defer></script>
</head>
<body  class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Gestor de Tareas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @can('tablero de tareas')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tareas.index') }}">Tablero</a>
                    </li>
                    @endcan
                    @can('ver proyectos')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('proyectos.index') }}">Proyectos</a>
                    </li>
                    @endcan
                    @can('crear tareas')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tareas.create') }}">Crear Tarea</a>
                    </li>
                    @endcan
                    @can('tareas archivadas')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tareas.archivo') }}">Tareas archivadas</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
