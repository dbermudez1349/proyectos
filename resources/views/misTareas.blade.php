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

@endsection
@section('scripts')

    <script src="{{ asset('js/tarea/consultar.js?v=' . rand())}}"></script>
    <script>
        $('#tituloCabecera').html('')
        verMisTareas()

    </script>

@endsection