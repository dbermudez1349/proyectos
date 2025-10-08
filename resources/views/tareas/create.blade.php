@extends('layouts.app')

@section('content')

    
    <section class="content-header" id="arriba">
        <h1>
            Nueva Tarea 
        </h1>

    </section>

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

              

                    <form class="form-horizontal" id="form_tareas" autocomplete="off" method="post"
                        action="">
                        {{ csrf_field() }}

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Titulo</label>
                            <div class="col-sm-8">
                                <input type="text" minlength="1" maxlength="100" onKeyPress="if(this.value.length==100) return false;" class="form-control proy_class" id="titulo" name="titulo" placeholder="Titulo">
                            </div>
                        
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Descripción</label>
                            <div class="col-sm-8">
                                <textarea minlength="1" maxlength="300" onKeyPress="if(this.value.length==300) return false;" class="form-control proy_class" id="descripcion" name="descripcion" placeholder="Descripción"></textarea>
                            </div>
                        
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Asignar Area</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Una Area"  multiple="multiple" style="width: 100%;" class="form-control select2 proy_class1" name="areas[]" id="areas" >
                                    @foreach ($area as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id}}" >{{ $dato->descripcion }} </option>
                                    @endforeach                                           
                                    
                                </select>                                    
                            </div>                                
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Proyecto</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Proyecto " style="width: 100%;" class="form-control select2 proy_class2" name="proyecto" id="proyecto" >
                                    
                                </select>                                    
                            </div>                                
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Fecha Limite</label>
                            <div class="col-sm-8">
                                <input type="date"  class="form-control proy_class" id="flimite" name="flimite" placeholder="Titulo">
                            </div>
                        
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12 text-center" >
                            
                                <button type="submit" class="btn btn-success btn-sm">
                                    Guardar
                                </button>
                                <button type="button" onclick="limpiarCampos()" class="btn btn-warning btn-sm">
                                    Cancelar
                                </button>
                                
                            </div>
                        </div>
                            
                           
                    
                    </form>

                
            </div>
        </div>
    </section>
@endsection
@section('scripts')

    <script src="{{ asset('js/tarea/generar.js?v='.rand())}}"></script>
    <script>
        $('#tituloCabecera').html('Buscar')
        const hoy = new Date().toISOString().split('T')[0];
    
        // Asignarla como valor mínimo al input
        document.getElementById('flimite').setAttribute('min', hoy);
       
    </script>

@endsection