@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{asset('bower_components/sweetalert/sweetalert.css')}}">
    <section class="content-header">
        <h1>
            Gestión Roles
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_user">
            <div class="box-header with-border">
                <h3 class="box-title">Listado </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    
                </div>

              
            </div>
            <div class="box-body">

                <!-- <div class="col-md-12" style="text-align:right; margin-bottom:20px; margin-top:10px">
                    <button type="button" onclick="visualizarForm('N')" class="btn btn-primary btn-sm">Nuevo</button>
                </div> -->

                <div class="table-responsive">
                    <table id="tabla_usuario" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rol</th>
                                <th>Permisos</th>
                               
                                <th style="min-width: 30%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4"><center>No hay Datos Disponibles</td>
                            </tr>
                            
                        </tbody>
                      
                    </table>  
                </div>    

                
            </div>

        </div>


    </section>

      <div class="modal fade_ detalle_class"  id="modal_Permisos" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">AGREGAR PERMISOS</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <form class="form-horizontal" id="form_registro_permisos" autocomplete="off" method="post"
                                action="">
                                {{ csrf_field() }}
                               
                                <div class="form-group">
    
                                    <label for="inputPassword3" class="col-sm-3 control-label">Rol</label>
                                    <div class="col-sm-7">
                                        <input type="hidden" name="id_rol_selecc" id="id_rol_selecc">
                                        <input type="text" readonly class="form-control act_user" id="rol_selecc" name="rol_selecc"  >
                                    </div>
                                
                                </div>

                                <div class="form-group">
    
                                    <label for="inputPassword3" class="col-sm-3 control-label">Permisos</label>
                                    <div class="col-sm-7">
                                        <select data-placeholder="Seleccione Un Permiso" style="width: 100%;" class="form-control select2" name="permisos" id="permisos" >
                                            @if(isset($permisos))
                                                @foreach ($permisos as $dato)
                                                    <option value=""></option>
                                                    <option value="{{ $dato->id}}" >{{ $dato->name }} </option>
                                                @endforeach
                                            @endif
                                        </select>
    
                                    </div>
                                
                                </div>


                                <div class="form-group">
                                    <div class="col-sm-12 col-md-offset-3 " >
                                    
                                        <button type="submit" class="btn btn-success btn-sm">
                                            Guardar
                                        </button>
                                        <button type="button" onclick="cancelarPermiso()" class="btn btn-danger btn-sm">Cancelar</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>

                        <div class="table-responsive col-md-12">
                            <table id="tabla_permisos" width="100%"class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripcion</th>
                                                                           
                                        <th style="min-width: 30%">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="3"><center>No hay Datos Disponibles</td>
                                    </tr>
                                    
                                </tbody>
                            
                            </table>  
                        </div> 

                        
                        
                    </div>
    
                    
                </div>
                
            </div>
    
        </div>
    
    </div>


@endsection
@section('scripts')
    <script src="{{asset('bower_components/sweetalert/sweetalert.js')}}"></script>
    <script src="{{ asset('js/gestionRol/permiso.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_rol()
        // limpiarCampos()
    </script>


@endsection
