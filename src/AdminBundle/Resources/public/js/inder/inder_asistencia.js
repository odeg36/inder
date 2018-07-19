'use strict';

inder.asistencia = {
    registroManual: 'registroManual',
    registroExcel: 'registroExcel',
    oferta: 0,
    dia: 0,
    asistentes: [],
    checkSeleccionHorario: function (element) {
        $("input.asistio_todos").iCheck('enable');
        inder.asistencia.dia = $(element).val();
        var data = {
            id_programacion : $(element).val()
        };
        var url = Routing.generate('ajax_traer_dia_programado_asistencia');
        var select_seleccion_dia_unico = $("#asistencia_oferta_seleccion_dia_unico");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_fecha_asistencia");
            },
            success: function (data) {
                select_seleccion_dia_unico.html('<option value ="">'+ Translator.trans('seleccionar.fecha') +'</option>');
                $(data).each(function(key, item){
                    select_seleccion_dia_unico.append('<option value="' + item + '">' + item + '</option>');
                });

                select_seleccion_dia_unico.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_fecha_asistencia");
            }
        });
    },
    cambioSeleccionDia: function(elemento, limpiar){
        $("input.asistio_todos").iCheck('enable');
        if($(elemento).val() != 0 && $(elemento).val() != null){
            $("div.contenedor-asistencia").show();
            inder.asistencia.seleccionDiaUnicoProgramacionOferta($(elemento), limpiar);
        }else{
            $(".checkbox-clear").each(function(key, item){
                if($(item).is(":checked")){
                    $(item).iCheck('toggle');
                }
                $(item).iCheck('enable');
            });
        }
    },
    seleccionDiaUnicoProgramacionOferta: function (element, limpiar) {
        var valor = $(element).val();
        var data = {
            fecha: valor
        };
        
        $("div." + valor).show();
        var fecha = $("select.seleccion_dia_unico").val();
        var url = Routing.generate('usuarios_oferta_preinscritos', { oferta: inder.asistencia.oferta, fecha: fecha });
        
        if(limpiar == false){
            return true;
        }
        $.ajax({
            type: 'get',
            url: url,
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                $("div.cargando").show();
            },
            complete: function (jqXHR, textStatus) {
                $("div.cargando").hide();
            },
            success: function (data) {
                if(data.length <= 0){
                    $(".checkbox-clear").each(function(key, item){
                        $(item).iCheck('toggle');
                        $(item).iCheck('enable');
                    });
                }else{
                    $("input.checkbox-clear").iCheck('disable');
                    $("input.asistio_todos").iCheck('disable');
                }

                $(data).each(function(key, item){
                    if(item.usuario && item.asistio){
                        $(".checkbox-" + item.usuario.username).iCheck('toggle');
                        $(".checkbox-" + item.usuario.username).iCheck('disable');
                    }
                });
            }
        });
    },
    usuario: function (elemento, id, oferta) {
        var valor = $(elemento).val() ? $(elemento).val() : 0;

        if ($(elemento).attr("id").search("fake") >= 0) {
            return true;
        }

        $(".nombre_usuario_" + id + " h5").text("");
        $("input.nombre_usuario_oculto_" + id).val("");

        if (!valor) {
            $("div.formulario_acompanate_" + id).html("");

            $(".nombre_usuario_" + id + " h5").text("");
            $("input.nombre_usuario_oculto_" + id).val("");
            
            return true;
        }

        $.post(Routing.generate('existe_usuario_deportista'), {id: valor}, function (data) {
            var nombre = '';
            if (data.firstname) {
                nombre += data.firstname;
            }
            if (data.lastname) {
                nombre += " " + data.lastname;
            }
            $(".nombre_usuario_" + id + " h5").text(nombre);
            $("input.nombre_usuario_oculto_" + id).val(nombre);
        });
    },
    validarCheckbox: function(limpiar){
        $('input.seleccion_tipo_carga').each(function(key, item){
            if($(item).prop('checked') === true){
                inder.asistencia.seleccionDiaUnicoProgramacionOferta($(item), limpiar);
            };
        });
    },
    removerCollectionAcction: function(elemento){
        $(elemento).find('div.collection-actions').remove();
    },
    habilitarBotonEliminar: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.asistencia-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },
    generaraReporte: function (elemento){
        var oferta = $(elemento).attr("data-oferta");
        var dia = $('select.seleccion_dia_unico').val();
    },
    selectIfChecked: function(elemento){
        if($(elemento).is(":checked")){
            $(".checkbox-clear").each(function(key, item){
                if(!$(item).is(":checked")){
                    $(item).iCheck('toggle');
                }
            });
        }else{
            $(".checkbox-clear").each(function(key, item){
                if(!$(item).is(':disabled')){
                    $(item).iCheck('uncheck');
                }
            });
        }
    },
    setUsuariosAsistentes: function(elemento){
        var id = $(elemento).attr("data-id-usuario");
        
        inder.asistencia.asistentes.push(id);
        
        if(inder.asistencia.asistentes.length > 0){
            $("textarea.usuariosAsistentes").val(JSON.stringify(inder.asistencia.asistentes));
        }
    },
    limpiarFormulario: function(){
        $("form").submit(function (elemento) {
            inder.asistencia.asistentes = [];
            $("div.contenedor-asistentes").hide();
            $("div.cargando").show();
            $("input.checkbox-clear").each(function(key, item){
                if(item.checked == true){
                    inder.asistencia.setUsuariosAsistentes(item);
                }
                $(item).parents("div.elemento-hijo-asistencia_oferta").remove();
            });
        });
    }
};

$(document).ready(function(){
    inder.asistencia.selectIfChecked($('input.asistio_todos'));
    
    $('input.seleccion_tipo_carga').on('ifChecked', function (event) {
        inder.asistencia.seleccionDiaUnicoProgramacionOferta($(this), true);
    });
    
    inder.asistencia.limpiarFormulario();
});