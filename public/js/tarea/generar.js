$('#proyecto').select2({

    placeholder: 'Seleccione una opción',
    ajax: {
    url: "buscar-proyectos",
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

 globalThis.AccionForm="R";

$("#form_tareas").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let titulo=$('#titulo').val()
    let descripcion=$('#descripcion').val()
    let area=$('#areas').val()
    let proyecto=$('#proyecto').val()
    let fecha_limite=$('#flimite').val()
    
    if(titulo=="" || titulo==null){
        alertNotificar("Debe ingresar el titulo","error")
        $('#titulo').focus()
        return
    } 

    if(descripcion=="" || descripcion==null){
        alertNotificar("Debe ingresar la descripcion","error")
        $('#descripcion').focus()
        return
    } 

    if(area=="" || area==null){
        alertNotificar("Debe seleccionar al menos una area","error")
        return
    } 

    if(proyecto=="" || proyecto==null){
        alertNotificar("Debe seleccionar un proyecto","error")
        return
    }
    
    if(fecha_limite=="" || fecha_limite==null){
        alertNotificar("Debe seleccionar la fecha limite","error")
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
        url_form="../tareas"
    }else{
        tipo="PUT"
        url_form="tareas/"+idGestionMenuEditar
    }
  
    var FrmData=$("#form_tareas").serialize();

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
           
        }, error:function (data) {
            console.log(data)

            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('.proy_class').val('')
    $('.proy_class2').val('').trigger('change.select2')
    // $('.proy_class1').val('').trigger('change');
    $('.proy_class1').val([]).trigger('change');

}