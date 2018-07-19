'use strict';

inder.asistencia.reserva = {
    reserva: 0,
    dia: 0,
    checkSeleccionHorario: function (element) {
        $("input.asistio_todos").iCheck('enable');
        inder.asistencia.reserva.dia = element.val();
        var data = {
            id_programacion: element.val(),
            reserva_id: inder.asistencia.reserva.reserva
        };
        var url = Routing.generate('ajax_traer_dia_programado_asistencia_reserva');
        var select_seleccion_dia_unico = $("#asistencias_reserva_seleccion_dia_unico");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_fecha_asistencia_reserva");
            },
            success: function (data) {
                select_seleccion_dia_unico.html('<option>' + Translator.trans('seleccionar.fecha') + '</option>');
                $(data).each(function (key, item) {
                    select_seleccion_dia_unico.append('<option value="' + item + '">' + item + '</option>');
                });

                select_seleccion_dia_unico.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_fecha_asistencia_reserva");
            }
        });
    },
    cambioSeleccionDia: function (elemento, limpiar) {
        $("a.generarReporte").attr("disabled", "disabled");
        $("a.generarReporte").removeAttr("href");
        $("input.asistio_todos_reserva").iCheck('enable');

        if ($(elemento).val() != 0 && $(elemento).val() != null) {
            $("div.contenedor-asistencia").show();
            inder.asistencia.validarCheckbox(limpiar);
            inder.asistencia.habilitarReporte = true;

            var url = Routing.generate('reporte_asistentes_reserva', {'reserva': inder.asistencia.reserva.reserva, 'fecha': $(elemento).val(), 'dia': inder.asistencia.reserva.dia});

            $("a.generarReporte").removeAttr("disabled");
            $("a.generarReporte").attr("href", url);

            inder.asistencia.reserva.seleccionDiaUnicoProgramacionReserva($("select.seleccion_dia_unico_reserva"), limpiar);
        } else {
            $(".checkbox-clear").each(function (key, item) {
                if ($(item).is(":checked")) {
                    $(item).iCheck('toggle');
                    $(item).iCheck('enable');
                }
            });
        }
    },
    eliminarHijos: function () {
        return true;
        $("div.asistencia-hijo-eliminar").remove();
    },
    seleccionDiaUnicoProgramacionReserva: function (element, limpiar) {
        var valor = $(element).val();
        var data = {
            fecha: valor
        };

        $("div." + valor).show();
        var fecha = $("select.seleccion_dia_unico_reserva").val();
        if (fecha != "Seleccione una fecha") {
            var url = Routing.generate('usuarios_reserva', {reserva: inder.asistencia.reserva.reserva, fecha: fecha});

            if (limpiar == false) {
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
                    if (data.length <= 0) {
                        $(".checkbox-clear").each(function (key, item) {
                            if ($(item).is(":checked")) {
                                $(item).iCheck('toggle');
                                $(item).iCheck('enable');
                            }
                        });
                        $("input.asistio_todos_reserva").iCheck('enable');
                    }else{
                        $("input.checkbox-clear").iCheck('disable');
                        $("input.asistio_todos_reserva").iCheck('disable');
                    }
                    
                    $(data).each(function (key, item) {
                        if (item.usuario && item.asistio) {
                            $(".checkbox-" + item.usuario.username).iCheck('toggle');
                            $(".checkbox-" + item.usuario.username).iCheck('disable');
                        }
                    });
                }
            });
        }else{
            $("input.checkbox-clear").iCheck('disable');
            $("input.asistio_todos_reserva").iCheck('enable');
            $(".checkbox-clear").each(function(key, item){
                $(item).iCheck('uncheck');
                $(item).iCheck('enable');
            });
        }
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
    removerCollectionAcction: function (elemento) {
        $(elemento).find('div.collection-actions').remove();
    },
    habilitarBotonEliminar: function () {
        $("input.btn_remover").each(function (key, item) {
            var div = $(item).parents("div.asistencia-hijo-eliminar");
            if (!$(item).val()) {
                $(div).find("a.hidden").removeClass("hidden");
            } else {
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },
    generaraReporte: function (elemento) {
        var oferta = $(elemento).attr("data-oferta");
        var dia = $('select.seleccion_dia_unico_reserva').val();
    },
    selectIfChecked: function(accion){
        if(accion){
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
    }
};

$(document).ready(function () {
    $('input.dias_semana_programacion_reserva').on('ifChecked', function (event) {
        inder.asistencia.reserva.checkSeleccionHorario($(this));
    });
    
    $('input.asistio_todos_reserva').on('ifChecked', function (event) {
        inder.asistencia.reserva.selectIfChecked(true);
    });
    $('input.asistio_todos_reserva').on('ifUnchecked', function (event) {
        inder.asistencia.reserva.selectIfChecked(false);
    });
});