$('#titulo').select2({

    placeholder: 'Seleccione una opción',
    ajax: {
    url: "buscar-tareas",
    dataType: 'json',
    delay: 250,
    data: function (params) {
        return {
            q: params.term, // Search term
            // param1:BodegaSeleccionada,
        };
    },
    processResults: function (data) {
        return {
        results:  $.map(data, function (item) {
                return {
                    text: item.detalle,
                    id: item.id
                }
            })
        };
    },
    cache: true
    }
})

$("#form_consultar").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let titulo=$('#titulo').val()
    let areas=$('#areas').val()
    let estado=$('#estado').val()

    $("#tabla_proyecto tbody").html('');

	$('#tabla_proyecto').DataTable().destroy();
	$('#tabla_proyecto tbody').empty(); 
   
    vistacargando("m","Espere por favor")
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    let tipo="POST"
    let url_form="tareas/consultarTareas"
   
    var FrmData=$("#form_consultar").serialize();

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
            
            
            if(data.error==false){
            
                if(data.resultado.length <= 0){
                    $("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                    alertNotificar("No se encontró datos","error");
                    return;  
                }
                
                $('.div_tabla').show()

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
                        { "width": "25%", "targets": 0 },
                        { "width": "35%", "targets": 1 },
                        { "width": "10%", "targets": 2 },
                        { "width": "10%", "targets": 3 },
                        { "width": "10%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                    
                    ],
                    data: data.resultado,
                    columns:[
                            {data: "titulo"},
                            {data: "descripcion" },
                            { 
                                data: "areas",
                                title: "Áreas",
                                render: function (areas) {
                                    if (!areas || areas.length === 0) return '<em>Sin área</em>';
                                    let lista = '<ul class="mb-0">';
                                    areas.forEach(a => lista += `<li>${a}</li>`);
                                    lista += '</ul>';
                                    return lista;
                                }
                            },
                            {data: "estado"},
                            {data: "fecha_limite"},
                            {data: "descripcion"},
                    ],    
                    "rowCallback": function( row, data, index ) {
                        // $('td', row).eq(0).html(index+1)
                        $('td', row).eq(5).html(`
                                    
                                                <button type="button" class="btn btn-primary btn-xs" onclick="verDetalle(${data.id })">Ver</button>
                                                                                    
                                                <a onclick="eliminarGestionMenu(${data.id })" class="btn btn-danger btn-xs"> Eliminar </a>
                                        
                                        
                        `); 

                        $(row).removeClass('estado-pendiente estado-progreso estado-completada');

                        if (data.estado === "Pendiente") {
                            $(row).addClass('estado-pendiente');
                        } else if (data.estado === "En Proceso") {
                            $(row).addClass('estado-progreso');
                        } else if (data.estado === "Completada") {
                            $(row).addClass('estado-completada');
                        }
                    }             
                });
            }
           
        }, error:function (data) {
            console.log(data)

            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})


function ocultaTabla(){
     $('.div_tabla').hide()
}

function verMisTareas(){
    $("#tabla_proyecto tbody").html('');

	$('#tabla_proyecto').DataTable().destroy();
	$('#tabla_proyecto tbody').empty(); 

    var num_col = $("#tabla_proyecto thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);

     $.get("mis-proyectos-listar/", function(data){
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
                    { "width": "25%", "targets": 0 },
                    { "width": "35%", "targets": 1 },
                    { "width": "10%", "targets": 2 },
                    { "width": "10%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                   
                
                ],
                data: data.resultado,
                columns:[
                        
                        {data: "titulo"},
                       
                        {data: "descripcion" },
                        {data: "estado"},
                        {data: "fecha_limite"},
                        {data: "descripcion"},
                ],    
                "rowCallback": function( row, data, index ) {
                    // $('td', row).eq(0).html(index+1)
                    $('td', row).eq(4).html(`
                                
                                            <button type="button" class="btn btn-success btn-xs" onclick="verDetalle(${data.id })">Completar</button>
                                            
                                    
                    `); 

                    $(row).removeClass('estado-pendiente estado-progreso estado-completada');

                    if (data.estado === "Pendiente") {
                        $(row).addClass('estado-pendiente');
                    } else if (data.estado === "En Proceso") {
                        $(row).addClass('estado-progreso');
                    } else if (data.estado === "Completada") {
                        $(row).addClass('estado-completada');
                    }
                }             
            });
        }
    }).fail(function(){
        $("#tabla_proyecto tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


   
}
globalThis.IdDetalleAct=0

function verDetalle(id){
    IdDetalleAct=id
    $('#content_proyecto_act').html('')
    vistacargando("m", "Espere por favor...")
    $.get("detalle-tarea/"+id, function(data){
        vistacargando("")
        console.log(data)
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        if(data.estadoMiTarea.estado=="Completado"){
            $('.finalizado').prop('disabled',true)
        }

        $('#tabla_data').hide(200)
        $('#detalle_data').show(200)

        $('#nombre_proyecto').html(data.resultado[0].nombre_proy)
        $('#descripcion_proyecto').html(data.resultado[0].descrpcion_proy)

        $('#nombre_tarea').html(data.resultado[0].titulo_tarea)
        $('#descripcion_tarea').html(data.resultado[0].desc_tarea)

        let areas = data.resultado[0].areas
        areas = areas.join(", ");
        $('#area_tarea').html(areas)

        $.each(data.actividades,function(i, item){
            
            console.log(item)
            let archivos = [];

            if (item.archivos) {
                try {
                    // decodifica doblemente si es string anidado
                    let parsed = JSON.parse(item.archivos);
                    if (typeof parsed === 'string') {
                        archivos = JSON.parse(parsed);
                    } else {
                        archivos = parsed;
                    }
                } catch (e) {
                    archivos = [];
                }
            }
            let id_act=item.id
            // construir la lista de archivos
            let listaArchivos = '';
            if (archivos.length > 0) {
                listaArchivos = '<ul>';
                $.each(archivos, function (i2, archivo) {
                    console.log(archivo)
                    let url = 'actividades/' +id_act+ '/'+i2;
                    listaArchivos += `
                        <li>
                            <a href="${url}" target="_blank" >
                                ${archivo.nombre}
                            </a>
                        </li>
                    `;
                });
                listaArchivos += '</ul>';
            } else {
                listaArchivos = '<p><i>No hay archivos adjuntos</i></p>';
            }

            $('#content_proyecto_act').append(`
                <ul class="timeline">

                            
                    <li class="time-label">
                        <span class="bg-light-blue" style="backgroud-color:#217e9f !important">
                            ${item.created_at}
                        </span>
                    </li>
                    
                    <li>
                        
                        <i class="fa fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                       

                            <h3 class="timeline-header" style="colr:black !important"><b>${item.area_name}</b></h3>

                            <div class="timeline-body" style="margin-left:10px; color:black !important">
                               ${item.comentario}<br>
                               
                                <p><b>Archivos</b></p>
                                ${listaArchivos}
                            </div>

                          
                        </div>
                    </li>                        
                </ul>

            `)
        })

    }).fail(function(){
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
  
}

function regresarListar(){
    $('#detalle_data').hide(200)
    $('#tabla_data').show(200)
}

function añadirActividad(){
    $('.act_user').val('')
    $('#id_tarea_act').val(IdDetalleAct)
    $('#modal_Actividad').modal('show')
}

function cancelarActividad(){
    $('#modal_Actividad').modal('hide')
}

$("#form_registro_act").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let comentario_act=$('#comentario_act').val()
    let archivo=$('#archivo').val()
    let id_tarea=$('#id_tarea_act').val()

    if(comentario_act=="" || comentario_act==null){
        alertNotificar("Debe ingresar el comentario","error")
        $('#comentario_act').focus()
        return
    } 
   
    vistacargando("m","Espere por favor")
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    let tipo="POST"
    let url_form="agregarActividad"
   
    // var FrmData=$("#form_registro_act").serialize();
    var FrmData = new FormData(this);

    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,   
        // processData:false, 
        contentType:false,
        cache:false,
        processData:false,

        success: function(data){
            vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
            
            if(data.error==false){
                
                alertNotificar(data.mensaje,'success');
                cancelarActividad()
                actualizarActividad(id_tarea)
            }
           
        }, error:function (data) {
            console.log(data)

            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function actualizarActividad(id_tarea){
    $('#content_proyecto_act').html('')
    vistacargando("m", "Espere por favor...")
    $.get("actualizar-actividad/"+id_tarea, function(data){
        vistacargando("")
        console.log(data)
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        $.each(data.resultado,function(i, item){

            let archivos = [];

            if (item.archivos) {
                try {
                    // decodifica doblemente si es string anidado
                    let parsed = JSON.parse(item.archivos);
                    if (typeof parsed === 'string') {
                        archivos = JSON.parse(parsed);
                    } else {
                        archivos = parsed;
                    }
                } catch (e) {
                    archivos = [];
                }
            }
            let id_act=item.id
            // construir la lista de archivos
            let listaArchivos = '';
            if (archivos.length > 0) {
                listaArchivos = '<ul>';
                $.each(archivos, function (i2, archivo) {
                    console.log(archivo)
                    let url = 'actividades/' +id_act+ '/'+i2;
                    listaArchivos += `
                        <li>
                            <a href="${url}" target="_blank" >
                                ${archivo.nombre}
                            </a>
                        </li>
                    `;
                });
                listaArchivos += '</ul>';
            } else {
                listaArchivos = '<p><i>No hay archivos adjuntos</i></p>';
            }


            $('#content_proyecto_act').append(`
                <ul class="timeline">

                            
                    <li class="time-label">
                        <span class="bg-light-blue" style="backgroud-color:#217e9f !important">
                            ${item.created_at}
                        </span>
                    </li>
                    
                    <li>
                        
                        <i class="fa fa-envelope bg-blue"></i>
                        <div class="timeline-item">
                       

                            <h3 class="timeline-header" style="colr:black !important"><b>${item.area_name}</b></h3>

                            <div class="timeline-body" style="margin-left:10px; color:black !important">
                               ${item.comentario}<br>
                                <p><b>Archivos</b></p>
                                ${listaArchivos}
                            </div>

                         

                          
                        </div>
                    </li>                        
                </ul>

            `)
        })


    }).fail(function(){
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });

}

function finalizarActividad(){
    
    if(confirm('¿Quiere dar por finalizado la tarea?')){
        vistacargando("m","Espere por favor")
        $.get("finalizar-tarea/"+IdDetalleAct, function(data){
            vistacargando("")
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            $('.finalizado').prop('disabled',true)
            verMisTareas()
        }).fail(function(){
            vistacargando("")
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
}

