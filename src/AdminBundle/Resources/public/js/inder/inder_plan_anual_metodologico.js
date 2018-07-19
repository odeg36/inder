'use strict';
var idFormulario = $('#idFormulario').attr('data-idForm');
inder.plan_anual_metodologico = {
    datosBasicosPlanAnual: function (datoSelect) { 
        if($(datoSelect).val() == 1){
            $('#datosBasicosId').show();
        }else{
            $('#datosBasicosId').hide();        
        }
        inder.plan_anual_metodologico.asignarNombre();
    },

    disciplinaPlanAnual: function (datoSelect) { 
        if($("#plan_anual_metodologico_clasificacion option:selected").val() == 1){
            $('#disciplinaId').show();
        }else{
            $('#disciplinaId').hide();        
        }
        inder.plan_anual_metodologico.asignarNombre();
    },

    ponderacionCom: function(){
        var numPonderaciones = $('.ponderacionOff').length;
        if($('#ponderacionComponentes').prop("checked") == true){
            $('.ponderacionOff').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacionOff').val(ponderacion);
        }else{
            $('.ponderacionOff').prop("disabled", false);        
        }
    },

    ponderacionCom2: function(){
        var numPonderaciones = $('.ponderacionOff').length;
        if($('#ponderacionComponentes').prop("checked") == true){
            $('.ponderacionOff').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacionOff').val(ponderacion);
        }else{
            $('.ponderacionOff').prop("disabled", false);        
        }
    },

    ponderacionCon: function(){
        var numPonderaciones = $('.ponderacion2Off').length;
        if($('#ponderacionContenidos').prop("checked") == true){
            $('.ponderacion2Off').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacion2Off').val(ponderacion);
        }else{
            $('.ponderacion2Off').prop("disabled", false);        
        }
    },

    ponderacionCon2: function(){
        var numPonderaciones = $('.ponderacion2Off').length;
        if($('#ponderacionContenidos').prop("checked") == true){
            $('.ponderacion2Off').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacion2Off').val(ponderacion);
        }else{
            $('.ponderacion2Off').prop("disabled", false);        
        }
    },
    
    asignarNombre: function(){
        var name = "";
        if (
            $("#plan_anual_metodologico_enfoque option:selected").text() != null &&
            $("#plan_anual_metodologico_enfoque option:selected").text() != "Seleccione una modalidad"
        ){
            if (
                $("#plan_anual_metodologico_enfoque option:selected").text() == "Técnico"
            ){
                name += $("#plan_anual_metodologico_enfoque option:selected").text() + " - ";
                if($("#plan_anual_metodologico_clasificacion option:selected").text() == "Disciplina"){
                    if (
                        $("#plan_anual_metodologico_disciplina option:selected").text() != null &&
                        $("#plan_anual_metodologico_disciplina option:selected").text() != "Seleccione una disciplina"
                    ){
                        name += $("#plan_anual_metodologico_disciplina option:selected").text() + " - ";
                    }
                    if (
                        $("#plan_anual_metodologico_niveles").val() != null 
                    ){
                        name += $("#plan_anual_metodologico_niveles option:selected").text() + " - ";
                    }
                }
            }else{
                name += $("#plan_anual_metodologico_enfoque option:selected").text() + " - v"+$("#datosBasicosId").attr('data-social')+ " - ";
            }
        }
        

        var anio = (new Date).getFullYear();
        name += anio;

        $('#mostrarNombre').text(name);
        $('#plan_anual_metodologico_nombre').val(name);
        
    },
    
    actualizarEstrategias: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            area_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_estrategias_por_area');
        var estrategia_selector = $("#plan_anual_metodologico_estrategias");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#estrategia_label");
            },
            success: function (data) {
                estrategia_selector.html('<option value="0">Seleccione una estrategia</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    estrategia_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                estrategia_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#estrategia_label");
            }
        });
    },

    /*actualizarOfertas: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            estrategia_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_ofertas_por_estrategia');
        var oferta_selector = $("#plan_anual_metodologico_estrategias");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#oferta_label");
            },
            success: function (data) {
                oferta_selector.html('<option value="0">Seleccione una oferta</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    oferta_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                oferta_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#oferta_label");
            }
        });
    },*/

    actualizarOfertas: function (datoSelect) {
        var idFormulario = $(datoSelect).attr('data-id');
        var data = {
            estrategia_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_ofertas_por_estrategia');

        var oferta_selector = $("#plan_anual_metodologico_estrategias_"+idFormulario+"_ofertas");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#oferta_label_"+idFormulario);
            },
            success: function (data) {
                oferta_selector.html('');
                for (var i = 0, total = data.length; i < total; i++) {
                    oferta_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                oferta_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#oferta_label_"+idFormulario);
            }
        });
    },

    removeComponente: function (e, id){
        $('#form_componente_'+id).remove();
        $('#delete_'+id).remove();
    },

    removeContenido: function (e, id){
        $('#form_contenido_'+id).remove();
        $('#delete_'+id).remove();
    },

    removeActividad: function (e, id){
        $('#form_actividad_'+id).remove();
        $('#delete_'+id).remove();
    },
    
    habilitarBotonEliminar: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.componente-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    habilitarBotonEliminarContenido: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.contenido-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    habilitarBotonEliminarActividad: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.actividad-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    setSelect2: function(id){
        $('#plan_anual_metodologico_componentes_'+id+'_modelo').select2();
        $('#plan_anual_metodologico_contenidos_'+id+'_componente').select2();
        $('#plan_anual_metodologico_actividades_'+id+'_contenidos').select2();
        $('#plan_anual_metodologico_actividades_'+id+'_tipoTiempoEjecucion').select2();
        $('#plan_anual_metodologico_estrategias_'+id+'_ofertas').select2();
        $('#plan_anual_metodologico_estrategias_'+id+'_estrategia').select2();
        $('#plan_anual_metodologico_estrategias_'+id+'_estrategia').attr('data-id', id);
    }
};

$(document).ready(function () {
    
    if($('#datosBasicosId').attr('data-enfoque') == 'Técnico'){
        $('#datosBasicosId').show();
    }else{
        $('#datosBasicosId').hide();        
    }

    if($('#disciplinaId').attr('data-clasificacion') == 'Disciplina'){
        $('#disciplinaId').show();
    }else{
        $('#disciplinaId').hide();        
    }

    $('#mostrarNombre span').text(name);

    $('#ponderacionComponentes').on('ifClicked', function(event){  
        var ischecked = event.target.checked; 
        var numPonderaciones = $('.ponderacionOff').length;     
        if(!ischecked){            
            $('.ponderacionOff').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacionOff').val(ponderacion);
        }else{
            $('.ponderacionOff').prop("disabled", false);        
        }    
    });

    if($('#ponderacionComponentes').prop("checked") == true){
        $('.ponderacionOff').prop("disabled", true);
    }

    $('#ponderacionContenidos').on('ifClicked', function(event){  
        var ischecked = event.target.checked; 
        var numPonderaciones = $('.ponderacion2Off').length;     
        if(!ischecked){            
            $('.ponderacion2Off').prop("disabled", true);
            var ponderacion = 100 / numPonderaciones;
            ponderacion = Number(ponderacion.toFixed(2));
            $('.ponderacion2Off').val(ponderacion);
        }else{
            $('.ponderacion2Off').prop("disabled", false);        
        }    
    });

    if($('#ponderacionContenidos').prop("checked") == true){
        $('.ponderacion2Off').prop("disabled", true);
    }
});