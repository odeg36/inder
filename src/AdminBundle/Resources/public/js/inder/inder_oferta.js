/* global inder */

'use strict';
var idFormulario = $('#idFormulario').attr('data-idForm');
inder.oferta = {
    validarDisciplinasRepetidas: function (select) {
        inder.oferta.validarRepetido(select, 'disciplinas', 'disciplina');
    },
    validarTendenciasRepetidas: function (select) {
        inder.oferta.validarRepetido(select, 'tendencias', 'tendencia');
    },
    validarCategoriasRepetidas: function (select) {
        inder.oferta.validarRepetido(select, 'institucionalEstrategias', 'categoria_institucional');
    },
    validarRepetido(select, selector, error) {
        var split = $(select).attr('id').split('_' + selector);
        var idForm = split[0];
        var valorSelect = $(select).val();
        var valorTextoSelect = $(select).find('option:selected').text();
        var divCollection = $('#field_container_' + idForm + '_' + selector);
        var elementoRepetido = 0;
        divCollection.find('select').each(function () {
            if ($(this).val() === valorSelect) {
                elementoRepetido++;
            }
        });
        /*if (elementoRepetido > 1) {
         swal({
         title: Translator.trans('titulo.error'),
         text: Translator.trans('error.estrategia_' + error + '.repetida', {valor: valorTextoSelect}),
         type: "error"
         },
         ).then(function () {
         $(select).val('').change();
         });
         }*/
    },

    /*validarRepetido(select, selector, error) {
     var split = $(select).attr('id').split('_' + selector);
     var idForm = split[0];
     var valorSelect = $(select).val();
     var valorTextoSelect = $(select).find('option:selected').text();
     var divCollection = $('#field_container_' + idForm + '_' + selector);
     var elementoRepetido = 0;
     divCollection.find('select').each(function () {
     if ($(this).val() === valorSelect) {
     elementoRepetido++;
     }
     });
     if (elementoRepetido > 1) {
     swal({
     title: Translator.trans('titulo.error'),
     text: Translator.trans('error.estrategia_' + error + '.repetida', {valor: valorTextoSelect}),
     type: "error"
     },
     ).then(function () {
     $(select).val('').change();
     });
     }
     },*/

    actualizarProyectos: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            area_id: $(datoSelect).val()
        };
        var url = Routing.generate("ajax_proyectos_por_area");
        var proyecto_selector = $("#" + idFormulario + "proyecto");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_proyecto");
            },
            success: function (data) {
                proyecto_selector.html('<option value="0">Seleccione un proyecto</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    proyecto_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                proyecto_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_proyecto");
            }
        });
    },
    actualizarEstrategias: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            proyecto_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_estrategias_por_proyecto');
        var estrategia_selector = $("#" + idFormulario + "estrategia");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_estrategia");
            },
            success: function (data) {
                estrategia_selector.html('<option value="0">Seleccione una estrategia</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    estrategia_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                estrategia_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_estrategia");
            }
        });
    },
    actualizarDisciplinas: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            estrategia_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_disciplinas_tendencias_por_estrategia');
        var disciplina_selector = $("#" + idFormulario + "disciplinaEstrategia");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_disciplina");
            },
            success: function (data) {
                disciplina_selector.html('<option value="">Seleccione una disciplina</option>');
                for (var i = 0, total = data["disciplina"].length; i < total; i++) {
                    disciplina_selector.append('<option value="' + data["disciplina"][i].id + '">' + data["disciplina"][i].nombre + '</option>');
                }
                disciplina_selector.change();
                var tendencia_selector = $("#" + idFormulario + "tendenciaEstrategia");
                tendencia_selector.html('<option value="">Seleccione una tendencia</option>');
                for (var i = 0, total = data["tendencia"].length; i < total; i++) {
                    tendencia_selector.append('<option value="' + data["tendencia"][i].id + '">' + data["tendencia"][i].nombre + '</option>');
                }
                tendencia_selector.change();
                var institucional_selector = $("#" + idFormulario + "institucionalEstrategia");
                institucional_selector.html('<option value="">Seleccione una categoría</option>');
                for (var i = 0, total = data["institucional"].length; i < total; i++) {
                    institucional_selector.append('<option value="' + data["institucional"][i].id + '">' + data["institucional"][i].nombre + '</option>');
                }
                institucional_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_disciplina");
            }
        });
    },
    actualizarBarrios: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_municipio');
        var barrio_selector = $("#" + idFormulario + "barrio");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
                $("div.puntoAtencion").addClass("hidden");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione una opción</option>');
                for (var i = 0, total = data.barrios.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data.barrios[i].id + '">' + data.barrios[i].nombre + '</option>');
                }
                barrio_selector.change();
                if (data.preguntar_vereda) {
                    $("div." + inder.formulario.registro.direccion).addClass("hidden");
                    $("div." + inder.formulario.registro.comuna).addClass("hidden");
                    $('.barrio').addClass('hidden');
                    $('.direccionOComuna').removeClass('hidden');
                } else {
                    $("div." + inder.formulario.registro.barrio).find('label').html(inder.formulario.registro.barrio_titulo);
                    $("div." + inder.formulario.registro.comuna).addClass("hidden");
                    $("div." + inder.formulario.registro.direccion).removeClass("hidden");
                    var check_1 = $('#formulario_registro_direccionOcomuna_0');
                    var check_2 = $('#formulario_registro_direccionOcomuna_1');
                    check_1.iCheck('uncheck');
                    check_2.iCheck('uncheck');
                    $('.barrio').removeClass('hidden');
                    $("div.direccion").removeClass("hidden");
                    $("div.puntoAtencion").removeClass("hidden");
                    $('.direccionOComuna').addClass('hidden');
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
    actualizarComunaBarrios: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            comuna_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_comuna');
        var barrio_selector = $("#" + idFormulario + "barrio");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione una opción</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
    actualizarComunaBarrios: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            comuna_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_comuna');
        var barrio_selector = $("#" + idFormulario + "barrio");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione una opción</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
    obtenerDivisiones: function (datoSelect) {
        var data = {
            escenario_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_divisiones_escenario');
        var division_selector = $('select.seleccion_division');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_division");
            },
            success: function (data) {
                division_selector.html("");
                division_selector.attr('placeholder', 'Seleccione una división');
                for (var i = 0, total = data.length; i < total; i++) {
                    division_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                division_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_division");
            }
        });
    },
    //actualizar disciplina
    actualizarDisciplina: function (datoSelect) {

        var idFormulario = $('#formulario_reserva_paso1').attr('id');

        var data = {
            escenario_id: $('#escenario_deportivo').val()
        };

        var url = Routing.generate('ajax_disciplinas_por_escenarios');

        var disciplina_selector = $("#disciplina");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {

                disciplina_selector.html('<option value="">Seleccione una disciplina</option>');

                for (var i = 0, total = data.length; i < total; i++) {
                    disciplina_selector.append('<option data-nombre="' + data[i].nombre + '" value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }

                disciplina_selector.change();

            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
    obtenerDatosEscenario: function (datoSelect) {

        var idFormulario = $('#formulario_reserva_paso2').attr('id');

        //aca tomamos el valor del id del escenario
        //por ahora quemamos el dato
        var data = {
            escenario_id: $("#identificadorEscenario").val(),
        };

        var url = Routing.generate('ajax_divisiones_por_escenarios');

        var division_selector = $(".lista-escenario");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {

            },
            success: function (data) {
                for (var i = 0, total = data.length; i < total; i++) {
                    division_selector.append('<li ><input type="checkbox" value="' + data[i].id + '">' + data[i].nombre + '</li>');
                }
                division_selector.change();
            },
            complete: function (jqXHR, textStatus) {

            }
        });
    },
    crearSeccionPuntoAtencion: function (checked) {
        $('.comuna-crear').addClass('hidden');
        $('.direccion-crear').addClass('hidden');
        var check_1 = $('[id*="direccionOcomuna_0"]');
        var check_2 = $('[id*="direccionOcomuna_1"]');
        var checkbox = $('.checkNuevoPuntoAtencion');
        var campoNuevo = $('.mostrarDirNueva');
        var campoCreado = $('.mostrarDirCreada');
        var checked = checkbox.parent('[class*="icheckbox"]').hasClass("checked");
        if (checked) {
            ($(".botonBuscarDireccion").length > 0) ? $(".botonBuscarDireccion").css("display", "none") : "";
            campoNuevo.parent().parent().removeClass('ocultar');
            campoNuevo.parent().parent().addClass('mostrar');
            campoCreado.parent().parent().removeClass('mostrar');
            campoCreado.parent().parent().addClass('ocultar');
            $(".localizacion").removeClass('hidden');
        } else {
            ($(".botonBuscarDireccion").length > 0) ? $(".botonBuscarDireccion").css("display", "block") : "";
            campoCreado.parent().parent().removeClass('ocultar');
            campoCreado.parent().parent().addClass('mostrar');
            campoNuevo.parent().parent().removeClass('mostrar');
            campoNuevo.parent().parent().addClass('ocultar');
            $(".localizacion").addClass('hidden');
        }
        if (checked != null && checked == true) {
            if (check_1.length > 0 || check_2.length > 0) {
                if (check_1.iCheck('update')[0].checked) {
                    $('.comuna-crear').removeClass('hidden');
                } else if (check_2.iCheck('update')[0].checked) {
                    $('.direccion-crear').removeClass('hidden');
                } else {
                    $('.direccion-crear').removeClass('hidden');
                }
            }
        }

    },
    actualizarPuntoAtencion: function () {
        var idFormulario = $('#idFormulario').attr('data-idForm');

        var check_1 = $('[id*="direccionOcomuna_0"]');
        var check_2 = $('[id*="direccionOcomuna_1"]');
        var direccion = "";
        if (check_1.length > 0 || check_2.length > 0) {
            if (check_1.iCheck('update')[0].checked) {
                direccion = inder.formulario.actualizarDireccionComuna();
            } else if (check_2.iCheck('update')[0].checked) {
                direccion = inder.formulario.actualizarDireccion();
            }
        }
        var data = {
            barrio_id: $("#" + idFormulario + "barrio").val(),
            direccion: direccion
        };
        var url = Routing.generate('ajax_puntos_atencion_por_direccion_barrio');
        var punto_atencion = $("#" + idFormulario + "puntoAtencion");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_puntoAtencion");
                punto_atencion.parent().find('.help-block').remove();
                punto_atencion.parent().find('.mostrarDirCreada:eq(0)').removeClass('has-error');
            },
            success: function (data) {
                if (data.length > 0) {
                    punto_atencion.html('<option value="0">' + Translator.trans('formulario.oferta.select_puntoAtencion') + '</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        punto_atencion.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    punto_atencion.change();
                } else {
                    var mensajeError = Translator.trans('formulario.oferta.no_hay_puntos');
                    var error = '<div class="help-block sonata-ba-field-error-messages">' +
                            '<ul class="list-unstyled">' +
                            '<li><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + mensajeError + '</li>' +
                            '</ul>' +
                            '</div>';
                    punto_atencion.parent().find('.mostrarDirCreada:eq(0)').append(error);
                    punto_atencion.parent().find('.mostrarDirCreada:eq(0)').addClass('has-error');
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_puntoAtencion");
            }
        });
    },
    seleccionEscenarioOPuntoAtencion: function () {
        var escenario = $('.seleccion-escenario');
        var puntoA = $('.seleccion-puntoAtencion');
        var element = $(".seleccion-lugar-oferta");
        var checked;
        element.each(function () {
            if ($(this).prop("checked")) {
                checked = $(this).val();
                if (checked === "true") {
                    escenario.removeClass('ocultar');
                    escenario.addClass('mostrar');
                    puntoA.removeClass('mostrar');
                    puntoA.addClass('ocultar');
                } else {
                    puntoA.removeClass('ocultar');
                    puntoA.addClass('mostrar');
                    escenario.removeClass('mostrar');
                    escenario.addClass('ocultar');
                }

                var textoRadio = $(this).parent().parent().find('span:eq(0)').text();
                if (textoRadio.trim() === Translator.trans('formulario.oferta.escenario_seleccion.seleccion')) {
                    $('select.seleccion-puntoAtencion').val('').change();
                } else if (textoRadio.trim() === Translator.trans('formulario.oferta.otro.seleccion')) {
//                    $('select.seleccion_escenario').val('').change();
                    $('select.seleccion_division').val('').change();
                }
                return false;
            }
        });

    },
    seleccionDisciplinaTendencia: function () {
        var element = $('.seleccionEstrategiaCobertura');
        var disciplinaEst = $(".disciplinaEstrategia");
        var tendenciaEst = $(".tendenciaEstrategia");
        var institucionalEst = $(".institucionalEstrategia");
        var checked;
        element.each(function () {
            if ($(this).prop("checked")) {
                checked = $(this).val();
                switch (checked) {
                    case "0":
                        disciplinaEst.parent().parent().removeClass('mostrar');
                        disciplinaEst.parent().parent().addClass('ocultar');
                        tendenciaEst.parent().parent().removeClass('mostrar');
                        tendenciaEst.parent().parent().addClass('ocultar');
                        institucionalEst.parent().parent().removeClass('mostrar');
                        institucionalEst.parent().parent().addClass('ocultar');
                        $("select.selector_disciplina").val('').trigger('change');
                        $("select.selector_tendencia").val('').trigger('change');
                        $("select.selector_institucional").val('').trigger('change');
                        break;
                    case "1":
                        disciplinaEst.parent().parent().removeClass('ocultar');
                        disciplinaEst.parent().parent().addClass('mostrar');
                        tendenciaEst.parent().parent().removeClass('mostrar');
                        tendenciaEst.parent().parent().addClass('ocultar');
                        institucionalEst.parent().parent().removeClass('mostrar');
                        institucionalEst.parent().parent().addClass('ocultar');
                        $("select.selector_tendencia").val('').trigger('change');
                        $("select.selector_institucional").val('').trigger('change');
                        break;
                    case "2":
                        disciplinaEst.parent().parent().removeClass('mostrar');
                        disciplinaEst.parent().parent().addClass('ocultar');
                        tendenciaEst.parent().parent().removeClass('ocultar');
                        tendenciaEst.parent().parent().addClass('mostrar');
                        institucionalEst.parent().parent().removeClass('mostrar');
                        institucionalEst.parent().parent().addClass('ocultar');
                        $("select.selector_disciplina").val('').trigger('change');
                        $("select.selector_institucional").val('').trigger('change');
                        break;
                    case "3":
                        disciplinaEst.parent().parent().removeClass('mostrar');
                        disciplinaEst.parent().parent().addClass('ocultar');
                        tendenciaEst.parent().parent().removeClass('mostrar');
                        tendenciaEst.parent().parent().addClass('ocultar');
                        institucionalEst.parent().parent().removeClass('ocultar');
                        institucionalEst.parent().parent().addClass('mostrar');
                        $("select.selector_disciplina").val('').trigger('change');
                        $("select.selector_tendencia").val('').trigger('change');
                        break;
                }
                return false;
            }
        });
    },
    asignarTipoUsuario: function (element) {
        var data = {
            tipoIdentificacion_id: $(element).val(),
            tipo_usuario: $(element).data("tipousuario"),
        };
        var url = Routing.generate('ajax_asignar_valor_tipo_identificacion');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
            }
        });
    },
    setTimeFormat: function () {
        setTimeout(function () {
            $('.time').inputmask('hh:mm');
            $('.time').attr('placeholder', 'hh:mm');
        }, 1000);
    }
};
$(document).ready(function () {
    var check_1 = $('[id*="direccionOcomuna_0"]');
    var check_2 = $('[id*="direccionOcomuna_1"]');
    inder.oferta.setTimeFormat();

    $('.hora_programacion').timeEntry({
        show24Hours: true,
        defaultTime: '00:00 AM'
    }).change();
    $("form").submit(function () {
        $(".dia_programacion").removeAttr("disabled");
    });

    inder.oferta.asignarTipoUsuario($("select.tipoDocumentoFormador"));
    inder.oferta.asignarTipoUsuario($("select.tipoDocumentoGestor"));

    $('input.seleccion-lugar-oferta').on('ifChecked', function (event) {
        inder.oferta.seleccionEscenarioOPuntoAtencion();
    });

    $(".direccionCreada").append("<div class='col-md-12'> \
            <button type='button' value='Buscar' class='botonBuscarDireccion btn btn-primary col-md-1'>Buscar</button> \
            <label class='col-md-9' id='errorBotonBuscarDireccion'/></label> \
            </div>");

    $(".botonBuscarDireccion").click(function (e) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var barrio = $("#" + idFormulario + "barrio").val();
        if (barrio == "0" || barrio.length == 0) {
            var boton = $(this);
            var label = boton.next();
            label.text(Translator.trans('formulario.oferta.seleccione_un_barrio'));
        } else {
            e.preventDefault();
            inder.oferta.actualizarPuntoAtencion();
            $("input.campoEscribirDireccion").select2("open");
            setTimeout(function () {
                inder.formulario.actualizarDireccion(".autocompletePuntoAtencion>div>.select2-input");
                setTimeout(function () {
                    $(".autocompletePuntoAtencion").find(".select2-input").change();
                }, 2000);
            }, 100);
            $("#errorBotonBuscarDireccion").text("");
        }
    });



    var campo = $('.mostrarDirNueva');
    campo.parent().parent().addClass('ocultar');
    var nuevoPuntoAtencion = $('.checkNuevoPuntoAtencion');
    nuevoPuntoAtencion.on('ifClicked', function (event) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        $(".botonBuscarDireccion").hide();
        $(".dato_direccion").val("");
        setTimeout(function () {
            inder.oferta.crearSeccionPuntoAtencion($('.checkNuevoPuntoAtencion').prop('checked'));
            $('#' + idFormulario + 'localizacion_map_canvas').ohGoogleMapType({
                'search_input_el': $('#' + idFormulario + 'localizacion_input'),
                'search_action_el': $('#' + idFormulario + 'localizacion_search_button'),
                'search_error_el': $('#' + idFormulario + 'localizacion_error'),
                'current_position_el': $('#' + idFormulario + 'localizacion_current_position'),
                'default_lat': '6.244203',
                'default_lng': '-75.5812119',
                'default_zoom': 5,
                'lat_field': $('#' + idFormulario + 'localizacion_latitud'),
                'lng_field': $('#' + idFormulario + 'localizacion_longitud'),
                'callback': oh_google_maps_callback
            });
        }, 1);
    });
    var seleccionEstrategiaCobertura = $('.seleccionEstrategiaCobertura');
    var disciplinaEst = $(".disciplinaEstrategia");
    var tendenciaEst = $(".tendenciaEstrategia");
    disciplinaEst.parent().parent().addClass('ocultar');
    tendenciaEst.parent().parent().addClass('ocultar');
    seleccionEstrategiaCobertura.on('ifClicked', function (event) {
        setTimeout(function () {
            inder.oferta.seleccionDisciplinaTendencia();
        }, 1);
    });


    $('.seleccion-puntoAtencion').change(function () {
        $(".seleccion-escenarioDeportivo").val("").change();
    });

    inder.oferta.seleccionEscenarioOPuntoAtencion();
    inder.oferta.seleccionDisciplinaTendencia();
    inder.oferta.crearSeccionPuntoAtencion($('.checkNuevoPuntoAtencion').prop('checked'));
    var municipio = $('#municipio');
    if (municipio.val() != "") {
        if (check_1.length > 0 || check_2.length > 0) {
            if (check_1.iCheck('update')[0].checked || check_2.iCheck('update')[0].checked) {
                $("div.direccionOComuna").removeClass("hidden");
                var opcion = "";
                if (check_1.iCheck('update')[0].checked) {
                    opcion = check_1.attr("choice-key");
                } else {
                    opcion = check_2.attr("choice-key");
                }
                if (inder.formulario.registro.direccion == opcion) {
                    $("div." + inder.formulario.registro.barrio).find('label').html(inder.formulario.registro.barrio_titulo);
                } else if (inder.formulario.registro.comuna == opcion) {
                    $("div." + inder.formulario.registro.barrio).find('label').html(inder.formulario.registro.vereda_titulo);
                }
                $("div." + opcion).removeClass("hidden");
                $("div.puntoAtencion").removeClass("hidden");
            } else {
                $("div." + inder.formulario.registro.direccion).removeClass("hidden");
                $("div.puntoAtencion").removeClass("hidden");
            }
            $("div." + inder.formulario.registro.barrio).removeClass("hidden");
        }
    }

});

