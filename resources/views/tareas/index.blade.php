@extends('layouts.app')

@section('content')

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


    </style>
    <section class="content-header" id="arriba">
        <h1>
            Listado Tareas
        </h1>

    </section>
    <div id="tabla_data">
        <section class="content" id="content_form">

            <div class="box" id="listado_persona">
                <div class="box-header with-border">
                    <h3 class="box-title" id="tituloCabecera"> </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        
                    </div>

                
                </div>
                <div class="box-body">

                    <div class="col-md-12" id="content_consulta">

                        <form class="role" id="form_consultar" autocomplete="off" method="post"
                            action="">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Tarea</label>
                                    <select data-placeholder="Seleccione Una Tarea " style="width: 100%;" class="form-control select2 proy_class2" name="titulo" id="titulo" onchange="ocultaTabla()" >
                                        
                                    </select>  
                                </div> 
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Areas</label>
                                    <select data-placeholder="Seleccione Una Area"  multiple="multiple" style="width: 100%;" class="form-control select2 proy_class1" name="areas[]" id="areas" onchange="ocultaTabla()"  >
                                        @foreach ($area as $dato)
                                            <option value=""></option>
                                            <option value="{{ $dato->id}}" >{{ $dato->descripcion }} </option>
                                        @endforeach 
                                        
                                    </select>
                                
                                </div> 
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Estado</label>
                                    <select data-placeholder="Seleccione Un Estado"  style="width: 100%;" class="form-control select2 proy_class1"  id="estado" name="estado" onchange="ocultaTabla()" >
                                    
                                        <option value="Todos" selected>Todos</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="En Progreso">En Progreso</option>
                                        <option value="Completada">Completada</option>
                                        
                                
                                    </select> 
                                </div>  
                                <div class="col-md-12" style="text-align:center">
                                    <button type="submit" class="btn btn-sm btn-primary">Consultar</button>
                                </div>                         
                            </div>
                        </form>

                        <div class="table-responsive div_tabla" style="margin-bottom:20px; margin-top:10px; display: none;" >
                            <table id="tabla_proyecto" width="100%"class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-center">Documento</th> -->
                                        <th class="text-center">Tarea</th>
                                        
                                        <th class="text-center">Descripcion</th>
                                        <th class="text-center">Areas </th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Fecha Limite</th>
                                    
                                        <th style="min-width: 30%" class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7"><center>No hay Datos Disponibles</td>
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
        <section class="content" id="content_detalle_proyecto" style="min-height: 0px; margin-bottom:-40px">
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
        </section>

        <section class="content" id="content_detalle_proyecto" style="min-height: 0px; margin-bottom:-40px">
            <div class="box" id="listado_persona">
                <div class="box-header with-border">
                    <h3 class="box-title" id="tituloCabecera"><b>DETALLE TAREA</b> </h3>

                
                </div>
                <div class="box-body">

                    <div class="col-md-12" id="content_proyecto">
                        <div class="col-md-6">
                            <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-tags text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Nombre</b>: <span  id="nombre_tarea">
                                        </span></a></li>
                                
                            </ul>

                             <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-tags text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Areas</b>: <span  id="area_tarea">
                                        </span></a></li>
                                
                            </ul>
                        
                        </div>  

                        <div class="col-md-6">
                            <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                                <li style="border-color: white"><a><i class="fa fa-indent text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Descripcion</b>: <span  id="descripcion_tarea">
                                        </span></a></li>
                                
                            </ul>
                            
                        </div>  
                    
                    </div>
                </div>
            </div>
        </section>

        <section class="content" id="content_detalle_proyecto">
            <div class="box" id="listado_persona">
                <div class="box-header with-border">
                    <h3 class="box-title" id="tituloCabecera"><b>ACTIVIDADES</b> </h3>

                
                </div>
                <div class="box-body">

                    <div class="col-md-12" id="content_proyecto_act">


                    </div>

                    <div class="box-footer">
                        <center>
                            <button type="button" onclick="regresarListar()" class="btn btn-danger">Regresar</button>
                        </center>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('scripts')

    <script src="{{ asset('js/tarea/consultar.js?v='.rand())}}"></script>
    <script>
        $('#tituloCabecera').html('Buscar')
       
    </script>

@endsection