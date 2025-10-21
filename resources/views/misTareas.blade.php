@extends('layouts.app')

@section('content')

    <style>
        .estado-pendiente {
            background-color: #fff3cd !important;
            /* amarillo suave */
        }

        .estado-progreso {
            background-color: #d1ecf1 !important;
            /* celeste */
        }

        .estado-completada {
            background-color: #d4edda !important;
            /* verde suave */
        }

        .timeline>li>.timeline-item>.time
       
        {
            color: #e72424;
            float: right;
            padding: 10x;  
            font-size: 18px;
        }

        .comentarioz{
           display: inline-block;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            color: #333;
            border-radius: 8px;
            padding: 6px 14px;
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            margin-left:14px
        }

       .timeline-item {
            position: relative;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            border-radius: 8px;
            z-index: 1;
        }

        .timeline-item:hover {
            background-color: #eee9e9ff !important; /* gris claro */
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            z-index: 2;
        }

        .observacion-actividad {
            display: inline-block;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            color: #333;
            border-radius: 8px;
            padding: 6px 14px;
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            margin-left: 14px;
            width: 98%;
            box-sizing: border-box;
        }

        /* En pantallas pequeñas */
        @media (max-width: 576px) {
            .observacion-actividad {
                width: 90%;
                margin-left: 5%; /* centra horizontalmente */
            }
        }

        .disabled {
            pointer-events: none;     /* desactiva clics */
            opacity: 0.5;             /* da efecto "apagado" */
            cursor: not-allowed;      /* muestra el cursor de prohibido */
            color: #999;              /* texto gris */
            background-color: #f0f0f0; /* fondo gris claro (opcional) */
            border-radius: 6px;       /* si tiene borde o fondo */
            transition: opacity 0.2s;
        }


    </style>
    <section class="content-header" id="arriba">
        <h1>
            Mis Tareas
        </h1>

    </section>

    <div id="tabla_data">
        <section class="content" id="content_form">

            <div class="box" id="listado_persona">
                <div class="box-header with-border">
                    <h3 class="box-title" id="tituloCabecera"> </h3>

                    <div class="box-tools pull-right">
                        <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                    <i class="fa fa-minus"></i>
                                </button> -->

                    </div>


                </div>
                <div class="box-body">

                    <div class="col-md-12" id="content_consulta">

                        <div class="table-responsive div_tabla" style="margin-bottom:20px; margin-top:10px; ">
                            <table id="tabla_proyecto" width="100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-center">Documento</th> -->

                                        <th class="text-center">Tarea</th>
                                        <th class="text-center">Descripcion</th>


                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Fecha Limite</th>

                                        <th style="min-width: 30%" class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">
                                            <center>No hay Datos Disponibles
                                        </td>
                                    </tr>

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="detalle_data" style="display:none">
        <!-- <section class="content" id="content_detalle_proyecto" style="min-height: 0px; margin-bottom:-40px">
                    <div class="box" id="listado_persona">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="tituloCabecera"><b>DETALLE PROYECTO</b></h3>

                        </div>
                        <div class="box-body">

                            <div class="col-md-12" id="content_proyecto" style="text-aling:center !important">
                                <div class="col-md-6">
                                    <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                        <li style="border-color: white"><a><i class="fa fa-ticket text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Nombre</b>: <span  id="nombre_proyecto">
                                                </span></a></li>

                                    </ul>

                                </div>  

                                <div class="col-md-6">
                                    <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                        <li style="border-color: white"><a><i class="fa fa-indent text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Descripcion</b>: <span  id="descripcion_proyecto">
                                                </span></a></li>

                                    </ul>

                                </div>  

                            </div>
                        </div>
                    </div>
                </section> -->



        <section class="content" id="content_detalle_proyecto" style="min-height: 0px; margin-bottom:-40px">
            <div class="box" id="listado_persona">
                <div class="box-header with-border">
                    <h3 class="box-title" id="tituloCabecera"><b>DETALLE TAREA</b> </h3>


                </div>
                <div class="box-body">

                    <div class="col-md-12" id="content_proyecto">
                        <div class="col-md-6">
                            <ul class="nav nav-pills nav-stacked" style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-tags text-blue"></i> <b
                                            class="text-black" style="font-weight: 650 !important">Nombre</b>: <span
                                            id="nombre_tarea">
                                        </span></a></li>

                            </ul>

                            <ul class="nav nav-pills nav-stacked" style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-tags text-blue"></i> <b
                                            class="text-black" style="font-weight: 650 !important">Areas</b>: <span
                                            id="area_tarea">
                                        </span></a></li>

                            </ul>

                            

                        </div>

                        <div class="col-md-6">
                            <ul class="nav nav-pills nav-stacked" style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-indent text-blue"></i> <b
                                            class="text-black" style="font-weight: 650 !important">Descripcion</b>: <span
                                            id="descripcion_tarea">
                                        </span></a></li>

                            </ul>

                            <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-indent text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Estado Tarea</b>: <span  id="estado_tarea">
                                        </span></a></li>
                                
                            </ul>

                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section class="content" id="content_detalle_proyecto">

            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab"><b>ACTIVIDADES</b></a></li>
                            <li><a href="#tab_2" data-toggle="tab"><b>DOCUMENTACION</b></a></li>
                            <li><a href="#tab_3" data-toggle="tab"><b>ESTADOS</b></a></li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">

                                <section class="content" id="content_detalle_proyecto">
                                    <div class="box" id="listado_persona">
                                        
                                        <div class="box-body">
                                            <div class="col-md-12" id="content_proyecto_act">
                                                <!-- Aquí puedes incluir tu timeline o contenido dinámico -->
                                            </div>


                                            <div class="box-footer">
                                                <center>
                                                    <button type="button" onclick="añadirActividad()"
                                                        class="btn btn-primary finalizado">Añadir Actividad</button>
                                                    <button type="button" onclick="finalizarActividad()"
                                                        class="btn btn-success finalizado">Finalizar</button>
                                                    <button type="button" onclick="regresarListar()"
                                                        class="btn btn-danger">Regresar</button>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <div class="table-responsive " style="margin-bottom:20px; margin-top:10px; ">
                                    <table id="tabla_documentos" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>  
                                                <th class="text-center">Archivos</th>
                                                <th class="text-center">Fecha</th>                                              
                                               
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <center>No hay Datos Disponibles
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                                <div class="box-footer">
                                    <center>
                                        <button type="button" onclick="regresarListar()"
                                            class="btn btn-danger">Regresar</button>
                                    </center>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="table-responsive " style="margin-bottom:20px; margin-top:10px; ">
                                    <table id="tabla_estado" width="100%" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>  
                                                <th class="text-center">Departamento</th>
                                                <th class="text-center">Estado</th> 
                                                <th class="text-center"></th>                                             
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="3">
                                                    <center>No hay Datos Disponibles
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                                <div class="box-footer">
                                    <center>
                                        <button type="button" onclick="regresarListar()"
                                            class="btn btn-danger">Regresar</button>
                                    </center>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
            </div>

            <!-- <div class="box" id="listado_persona">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="tituloCabecera"><b>ACTIVIDADES</b> </h3>


                        </div>
                        <div class="box-body">

                            <div class="col-md-12" id="content_proyecto_act">


                            </div>

                            <div class="box-footer">
                                <center>
                                    <button type="button" onclick="añadirActividad()" class="btn btn-primary finalizado">Añadir Actividad</button>
                                    <button type="button" onclick="finalizarActividad()" class="btn btn-success finalizado">Finalizar</button>
                                    <button type="button" onclick="regresarListar()" class="btn btn-danger">Regresar</button>
                                </center>
                            </div>
                        </div>
                    </div> -->
        </section>
    </div>

    <div class="modal fade_ detalle_class" id="modal_Actividad" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">AGREGAR ACTIVIDAD</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <form class="form-horizontal" id="form_registro_act" autocomplete="off" method="post" action="">
                                {{ csrf_field() }}

                                <div class="form-group">

                                    <label for="inputPassword3" class="col-sm-3 control-label">Comentario</label>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="id_tarea_act" id="id_tarea_act">
                                        <textarea minlength="1" maxlength="500"
                                            onKeyPress="if(this.value.length==500) return false;"
                                            class="form-control act_user" id="comentario_act" name="comentario"></textarea>
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="inputPassword3" class="col-sm-3 control-label">Archivos</label>
                                    <div class="col-sm-7">
                                        <input type="file" multiple id="archivo" name="archivos[]"
                                            class="form-control act_user">

                                    </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-sm-12 col-md-offset-3 ">

                                        <button type="submit" class="btn btn-success btn-sm">
                                            Guardar
                                        </button>
                                        <button type="button" onclick="cancelarActividad()"
                                            class="btn btn-danger btn-sm">Cancelar</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

     <div class="modal fade_ detalle_class" id="modal_DetalleReversion" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">DETALLE REVERSION</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="content_proyecto" style="text-aling:center !important">
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                    <li style="border-color: white"><a><i class="fa fa-user text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Usuario Revierte</b>: <span  id="usuario_revierte">
                                            </span></a></li>
                                    
                                </ul>

                                <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                    <li style="border-color: white"><a><i class="fa fa-edit text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Motivo Revierte</b>: <span  id="motivo_revierte">
                                            </span></a></li>
                                    
                                </ul>
                            
                            </div>  

                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                    <li style="border-color: white"><a><i class="fa fa-calendar text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Fecha Revierte</b>: <span  id="fecha_revierte">
                                            </span></a></li>
                                    
                                </ul>
                                
                            </div>  
                            
                    
                        </div>
                        <div class="col-md-12">
                            <center>
                                <button type="button" onclick="cerrarDetalleReversion()"
                                        class="btn btn-danger btn-sm">Cerrar</button>
                            </center>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade_ detalle_class" id="modal_Comentario" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">AGREGAR OBSERVACION</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12">
                            <form class="form-horizontal" id="form_registro_comentario" autocomplete="off" method="post" action="">
                                {{ csrf_field() }}

                                <div class="form-group">

                                    <label for="inputPassword3" class="col-sm-3 control-label">Observacion</label>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="id_tarea_act" id="id_tarea_act_observ">
                                        <textarea minlength="1" maxlength="500"
                                            onKeyPress="if(this.value.length==500) return false;"
                                            class="form-control act_user" id="observacion" name="observacion"></textarea>
                                        <input type="hidden" id="idactividad_observacion" name="idactividad_observacion">
                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="inputPassword3" class="col-sm-3 control-label">Archivos</label>
                                    <div class="col-sm-7">
                                        <input type="file" multiple id="archivos_observacion" name="archivos_observacion[]"
                                            class="form-control act_user">

                                    </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-sm-12 col-md-offset-3 ">

                                        <button type="submit" class="btn btn-success btn-sm">
                                            Guardar
                                        </button>
                                        <button type="button" onclick="cancelarObservacion()"
                                            class="btn btn-danger btn-sm">Cancelar</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>

@endsection
@section('scripts')

    <script src="{{ asset('js/tarea/consultar.js?v=' . rand())}}"></script>
    <script>
        $('#tituloCabecera').html('')
        verMisTareas()

    </script>

@endsection