@extends('layouts.app')

@section('content')
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

        .estado-pendiente {
            background-color: #fff3cd !important; /* amarillo suave */
        }

        .estado-progreso {
            background-color: #d1ecf1 !important; /* celeste */
        }

        .estado-completada {
            background-color: #d4edda !important; /* verde suave */
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        .chart-container {
            position: relative;
            height: 300px;
        }


    </style>
     <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!-- cajas pequeñas -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                <h3>{{sizeof($misTareasPendientesTotales)}}</h3>

                <p>Mis Pendientes</p>
                </div>
                <div class="icon">
                <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{route('misTareas')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
             </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{sizeof($proyectosTotales)}}</h3>
                <p>Proyectos</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
            <div class="inner">
                <h3>{{sizeof($tareasTotales)}}<sup style="font-size: 20px"></sup></h3>
                <p>Tareas</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{sizeof($usuarioTotales)}}</h3>
                <p>Usuarios</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
    </div>

    <!-- fila separada para las gráficas -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tareas</h3>

                </div>

                <div class="box-body">
                    <div class="chart">
                    <canvas id="salesChart" style="height:300px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de barras -->
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                <h3 class="box-title">Estado Tareas</h3>
                </div>
                <div class="box-body">
                <div class="chart">
                    <canvas id="barChart"></canvas>
                </div>
                </div>
            </div>
        </div>
    </div>

      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        
      </div>
      <!-- /.row (main row) -->

    </section>

@endsection
@section('scripts')

    <!-- <script src="{{ asset('js/tarea/consultar.js?v='.rand())}}"></script> -->
    <script>
        $('#tituloCabecera').html('')
        //verMisTareas()

        

        const ctx = document.getElementById('salesChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Tareas últimos 6 meses',
                    data: @json($data),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });


     

        const ctxBar = document.getElementById('barChart').getContext('2d');

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($categories),
                datasets: [{
                    label: 'Tareas por estado',
                    data: @json($barValues),
                    backgroundColor: [
                        'rgba(60,141,188,0.7)',
                        'rgba(0,166,90,0.7)',
                        'rgba(243,156,18,0.7)',
                        'rgba(221,75,57,0.7)',
                        'rgba(147,112,219,0.7)', // opcional, si tienes más estados
                    ],
                    borderColor: [
                        '#3c8dbc', '#00a65a', '#f39c12', '#dd4b39', '#9370DB'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 } // Ajusta según el número de tareas
                    }
                }
            }
        });
       
    </script>

@endsection