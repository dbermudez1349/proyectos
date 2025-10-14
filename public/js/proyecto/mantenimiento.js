$("#form_proyecto").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let nombre=$('#nombre').val()
    let descripcion=$('#descripcion').val()
   
    if(nombre=="" || nombre==null){
        alertNotificar("Debe ingresar el nombre","error")
        $('#nombre').focus()
        return
    } 

    if(descripcion=="" || descripcion==null){
        alertNotificar("Debe ingresar la descripcion","error")
        return
    } 
    vistacargando("m","Espere por favor")
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //comprobamos si es registro o edicion
    let tipo=""
    let url_form=""
    if(AccionForm=="R"){
        tipo="POST"
        url_form="proyectos"
    }else{
        tipo="PUT"
        url_form="proyectos/"+idGestionMenuEditar
    }
  
    var FrmData=$("#form_proyecto").serialize();

    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,      
		
        processData:false, 

        success: function(data){
            vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
            limpiarCampos()
            alertNotificar(data.mensaje,"success");
            $('#form_ing').hide(200)
            $('#listado_proyecto').show(200)
            llenar_tabla_proyecto()
                            
        }, error:function (data) {
            console.log(data)

            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    
    $('#nombre').val('')
    $('#descripcion').val('')
}

function llenar_tabla_proyecto(){
    var num_col = $("#tabla_proyecto thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("proyectos-listar/", function(data){
        console.log(data)
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_proyecto').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 1, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "10%", "targets": 0 },
                    { "width": "40%", "targets": 1 },
                    { "width": "40%", "targets": 2 },
                    { "width": "10%", "targets": 3 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "id"},
                        {data: "nombre" },
                        {data: "descripcion"},
                        {data: "id"},
                ],    
                "rowCallback": function( row, data, index ) {
                    $('td', row).eq(0).html(index+1)
                    $('td', row).eq(3).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarProyecto(${data.id })">Editar</button>
                                                                                
                                            <a onclick="eliminarGestionMenu(${data.id })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});



function editarProyecto(id){
    vistacargando("m","Espere por favor")
    $.get("proyectos/"+id, function(data){
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La persona ya no se puede editar","error");
            return;   
        }


        $('#nombre').val(data.resultado.nombre)
        $('#descripcion').val(data.resultado.descripcion)
      
        visualizarForm('E')
        globalThis.idGestionMenuEditar=id

       
    }).fail(function(){
        vistacargando("")
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_proyecto').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Proyecto")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualizar Proyecto")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_proyecto').show(200)
    limpiarCampos()
}

function eliminarGestionMenu(id){
    if(confirm('¿Quiere eliminar el registro?')){
        vistacargando("m","Espere por favor")
        // $.get("eliminar-gestion-menu/"+id, function(data){
        //     vistacargando("")
        //     if(data.error==true){
        //         alertNotificar(data.mensaje,"error");
        //         return;   
        //     }
    
        //     alertNotificar(data.mensaje,"success");
        //     llenar_tabla_proyecto()
           
        // }).fail(function(){
        //     vistacargando("")
        //     alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        // });

        $.ajax({
            url: "proyectos/" + id,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                vistacargando("")
                if(data.error==true){
                    alertNotificar(data.mensaje,"error");
                    return;   
                }
        
                alertNotificar(data.mensaje,"success");
                llenar_tabla_proyecto()
            },
            error: function (xhr) {
                vistacargando("")
                alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
            }
        });

    }
   
}