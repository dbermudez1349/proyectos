@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{asset('bower_components/sweetalert/sweetalert.css')}}">
    <section class="content-header">
        <h1>
            Gestión Usuario
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

                <div class="col-md-12" style="text-align:right; margin-bottom:20px; margin-top:10px">
                    <button type="button" onclick="visualizarForm('N')" class="btn btn-primary btn-sm">Nuevo</button>
                </div>

                <div class="table-responsive">
                    <table id="tabla_usuario" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Departamento</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th style="min-width: 30%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5"><center>No hay Datos Disponibles</td>
                            </tr>
                            
                        </tbody>
                      
                    </table>  
                  </div>    

                
            </div>

        </div>


        <div id="form_ing" style="display:none">
            <form class="form-horizontal" id="form_registro_user" autocomplete="off" method="post"
                action="">
                {{ csrf_field() }}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="titulo_form"> </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="form-group" style="">

                            <label for="inputPassword3" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" >
                            </div>
                           
                        </div>

                        
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Departamento</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Departamento" style="width: 100%;" class="form-control select2" name="departamento" id="departamento" >
                                    @if(isset($departamento))
                                        @foreach ($departamento as $dato)
                                            <option value=""></option>
                                            <option value="{{ $dato->id}}" >{{ $dato->descripcion }} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Rol</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Rol" style="width: 100%;" class="form-control select2" name="rol" id="rol" >
                                    @if(isset($rol))
                                        @foreach ($rol as $dato)
                                            <option value=""></option>
                                            <option value="{{ $dato->id}}" >{{ $dato->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                           
                        </div>

                        <div class="form-group" style="display:none">

                            <label for="inputPassword3" class="col-sm-3 control-label">Contraseña</label>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control" id="contrasenia" name="contrasenia" placeholder="Contraseña" value="123456">
                            </div>
                           
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12 text-center" >
                            
                                <button type="submit" class="btn btn-success btn-sm">
                                    <span id="nombre_btn_form"></span>
                                </button>
                                <button type="button" onclick="visualizarListado()" class="btn btn-danger btn-sm">Cancelar</button>
                            </div>
                        </div>
                        
                    </div>

                </div>
            
            </form>
        </div>



    </section>


@endsection
@section('scripts')
    <script src="{{asset('bower_components/sweetalert/sweetalert.js')}}"></script>
    <script src="{{ asset('js/gestionAcceso/usuario.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_usuario()
        // limpiarCampos()
    </script>


@endsection
