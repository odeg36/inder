'use strict';
inder.evento = {

    ajustarInputFile: function () {
        $('input.file').fileinput({
            language: "es"
        });
        $(".btn-default").attr("style", "display: none !important");
    },

    actualizarPuntoAtencion: function (datoSelect) {
        var barrio = $("#barrio").val();
        var data = {
            barrio_id: barrio,
            direccion: inder.formulario.actualizarDireccion()
        };
        var url = Routing.generate('ajax_puntos_atencion_por_direccion_barrio');
        var punto_atencion = $("#" + idFormulario + "puntoAtencion");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                if (data.length > 0) {
                    punto_atencion.html('<option value="0">Seleccione un punto de atencion</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        punto_atencion.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    punto_atencion.change();

                }
            }
        });
    },

    tipoReserva: function (element) {
        $(".seleccion-atencionPunto").hide();
        $(".seleccion-puntoEscenario").hide();
    },

    actualizarBarrios: function (datoSelect) {
        var data = {
            municipio_id: $("#municipio").val()
        };
        var url = Routing.generate('ajax_barrios_por_municipio');
        var barrio_selector = $("#barrio");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione un bar</option>');
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

    subcategorias: function (datoSelect) {
        var idFormulario = $(datoSelect).attr('data-id');
        if (idFormulario == undefined) {
            var idFormulario = $(datoSelect).attr('id');
            idFormulario = idFormulario.match(/\d+/g).map(Number);
            idFormulario = idFormulario[0];
        }
        var data = {
            categoria_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_subcategorias_por_categorias');
        var subCategoria_selector = $("#evento_categoriaSubcategorias_" + idFormulario + "_subcategorias");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                subCategoria_selector.html('');
                for (var i = 0, total = data.length; i < total; i++) {
                    subCategoria_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                subCategoria_selector.change();
            },
        });
    },

    subcategoriasIncripcion: function (datoSelect) {
        var data = {
            categoria_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_subcategorias_por_categorias');

        var subCategoria_selector = $("select.subcategoriasIncripcion");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                subCategoria_selector.html('');
                for (var i = 0, total = data.length; i < total; i++) {
                    subCategoria_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                subCategoria_selector.change();
                subCategoria_selector.select2({
                    width: '100%'
                });
            },

        });

    },

    divisiones: function (datoSelect)
    {
        var data = {
            escenario_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_divisiones_por_escenario');

        var division_selector = $("#evento_division");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                division_selector.html('');
                for (var i = 0, total = data.length; i < total; i++) {
                    division_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                division_selector.change();
            },

        });
    },

    puntos: function (datoSelect)
    {
        var data = {
            tipoFalta_id: $(datoSelect).val()
        };

        var url = Routing.generate('ajax_puntos_por_falta');

        var selector = $("#sanciones_puntaje_juego_limpio");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                selector.html('');
                document.getElementById("sanciones_puntaje_juego_limpio").innerHTML = data;
                $("#sanciones_puntaje_juego_limpio").val(data);
                selector.change();
            },

        });

    },

    puntosPorSancion: function (datoSelect)
    {
        var data = {
            sancion_id: $(datoSelect).val()
        };

        var url = Routing.generate('ajax_puntos_por_sancion');

        var selector = $("#sanciones_puntaje_juego_limpio");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                selector.html('');
                document.getElementById("sanciones_puntaje_juego_limpio").innerHTML = data;
                $("#sanciones_puntaje_juego_limpio").val(data);
                selector.change();
            },

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
                return false;
            }
        });

    },

    setSelect2: function (id) {
        $('#evento_usuarios_' + id + '_tipoIdentificacion').select2();
        $('#evento_categoriaSubcategorias_' + id + '_categoria').select2();
        $('#evento_categoriaSubcategorias_' + id + '_categoria').attr('data-id', id);
        $('#evento_categoriaSubcategorias_' + id + '_subcategorias').select2();
        $('#evento_usuarios_' + id + '_tipoIdentificacion').attr('data-id', id);
        $('#evento_usuarios_' + id + '_numeroIdentificacion').attr('data-id', id);
        $('#evento_usuarios_' + id + '_firstname').attr('disabled', true);
        $('#evento_usuarios_' + id + '_eventoRoles').select2();
        $('#equipo_evento_jugadorEventos_' + id + '_informacionExtraUsuario_tipoSangre').select2();

        $('#evento_usuarios_' + id + '_numeroIdentificacion').on('keyup', function (datoSelect) {

            var index = $(this).attr("data-id");
            var idTipoDocumento = $('#evento_usuarios_' + index + '_tipoIdentificacion').val();
            var numeroDocumento = $(this).val();

            var url = Routing.generate('ajax_usuario_por_numero_tipo_documento');

            var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento};
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    if (data.resultado.length == 1) {
                        if (data.resultado[0].id == data.query) {
                            var firstname = $('#evento_usuarios_' + index + '_firstname');
                            firstname.val(data.resultado[0].nombre);

                        }
                    } else {
                        var firstname = $('#evento_usuarios_' + index + '_firstname');
                        firstname.val('');
                    }
                }
            });

        });





    },
    setSelect2InscripcionEquipoUsuarioNatural: function (id) {
        //eddy
        $('#equipo_evento_jugadorEventos_' + id + '_usuarioJugadorEvento').attr('data-id', id);
        $('#equipo_evento_jugadorEventos_' + id + '_tipoIdentificacion').select2();
        ;
        $('#equipo_evento_jugadorEventos_' + id + '_usuarioJugadorEvento').on('keyup', function (datoSelect) {

            var index = $(this).attr("data-id");
            var idTipoDocumento = $('#equipo_evento_jugadorEventos_' + index + '_tipoIdentificacion').val();
            var numeroDocumento = $(this).val();

            var url = Routing.generate('ajax_usuario_por_numero_tipo_documento');

            var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento};
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    if (data.resultado.length == 1) {
                        if (data.resultado[0].id == data.query) {
                            var firstname = $('#equipo_evento_jugadorEventos_' + index + '_firstname');
                            firstname.val(data.resultado[0].nombre);

                        }
                    } else {
                        var firstname = $('#equipo_evento_jugadorEventos_' + index + '_firstname');
                        firstname.val('');
                    }

                }
            });

        });
    },

    asignarNoDocumentoEvento: function (datoSelect) {

        var idTipoDocumento = $(datoSelect).val();
        var url = Routing.generate('ajax_usuario_por_tipo_documento');

        var data = {
            idTipoDocumento: idTipoDocumento
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var inputNumeroDocumento = $("#numeroIdentificacion");

                inputNumeroDocumento.html('<option value="0">Seleccione un Usuario</option>');
                if (data.usuarios_existe == true) {
                    for (var i = 0, total = data.resultado.length; i < total; i++) {
                        inputNumeroDocumento.append('<option value="' + data.resultado[i].id + '">' + data.resultado[i].numeroIdentificacion + '</option>');
                    }
                    inputNumeroDocumento.change();
                } else {
                    inputNumeroDocumento.html('');
                }
            }
        });
    },

    asignarNombreEvento: function (datoSelect) {
        var id = $(datoSelect).attr('data-id');
        var url = Routing.generate('ajax_usuario_por_id');


        $('#evento_usuarios_' + id + '_numeroIdentificacion').on('keypress', function (datoSelect) {});

        var data = {
            id: $('#evento_usuarios_' + id + '_numeroIdentificacion').val()
        }

        $.ajax({
            type: 'post',
            url: url,
            data: data,

            success: function (data) {
                var nombre_selector = $('#evento_usuarios_' + id + '_firstname');
                if (data.usuario_existe == true) {
                    nombre_selector.val(data.resultado[0].nombre);

                } else {
                    nombre_selector.val(data.mensaje);
                }
            },

            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo('#evento_usuarios_' + id + '_firstname');
            }
        });




    },
};
$(document).ready(function () {

    //------------ Inscripion -------------------------------
    $(".classCheckForm").hide();

    if ($("#inscripcionPublica").val() != false) {
        $('#divTieneInscripcionPublica').show();
    }
    if ($("#preInscripcionPublica").val() != false) {
        $('#divTienePreinscripcionPublica').show();
    }
    if ($("#formularioGanador").val() != false) {
        $('#divCheckFormularioGanador').show();
    }
    if ($("#formularioRecambios").val() != false) {
        $('#divTieneFormularioRecambios').show();
    }

    /*$('#tieneInscripcionPublica').on('ifClicked', function (event) {
     var ischecked = event.target.checked;
     if (!ischecked) {
     $('#divTieneInscripcionPublica').show();
     } else {
     $('#divTieneInscripcionPublica').hide();
     }
     });
     
     $('#tienePreinscripcionPublica').on('ifClicked', function (event) {
     var ischecked = event.target.checked;
     if (!ischecked) {
     $('#divTienePreinscripcionPublica').show();
     } else {
     $('#divTienePreinscripcionPublica').hide();
     }
     });*/

    $('.inscripcionPublica').on('ifChecked', function () {
        $("#divTieneInscripcionPublica").show();
        $("#divTienePreinscripcionPublica").hide();
    });

    $('.preinscripcionPublica').on('ifChecked', function () {
        $("#divTieneInscripcionPublica").hide();
        $("#divTienePreinscripcionPublica").show();
    });

    $('#tieneFormularioGanador').on('ifClicked', function (event) {
        var ischecked = event.target.checked;
        if (!ischecked) {
            $('#divCheckFormularioGanador').show();
        } else {
            $('#divCheckFormularioGanador').hide();
        }
    });

    $('#tieneFormularioRecambios').on('ifClicked', function (event) {
        var ischecked = event.target.checked;
        if (!ischecked) {
            $('#divTieneFormularioRecambios').show();
        } else {
            $('#divTieneFormularioRecambios').hide();
        }
    });

    //----------------------- Configuracion ------------------------
    $(".direccionCreadaEvento").append("<div class='col-md-12'> \
            <button id='botonBuscarDireccionEvento' type='button' value='Buscar' class='btn btn-primary col-md-1'>Buscar</button> \
            <label class='col-md-9' id='errorBotonBuscarDireccionEvento'/></label> \
            </div>");

    $("#botonBuscarDireccionEvento").click(function (e) {
        var barrio = $("#barrio").val();
        var dato_direccion = $(".dato_direccion");
        var estatoDatoDireccion = true;
        dato_direccion.each(function () {
            if ($(this).val() != "") {
                estatoDatoDireccion = false;
                return false;
            }
        });
        if (barrio == "0" || barrio.length == 0 || estatoDatoDireccion) {
            $("#errorBotonBuscarDireccionEvento").text("Por favor seleccione un barrio y una direccion para realizar el filtro");
        } else {
            e.preventDefault();
            inder.evento.actualizarPuntoAtencion();
            $("input.campoEscribirDireccion").select2("open");
            setTimeout(function () {
                inder.formulario.actualizarDireccion(".autocompletePuntoAtencion>div>.select2-input");
                setTimeout(function () {
                    $(".autocompletePuntoAtencion").find(".select2-input").change();
                }, 2000);
            }, 100);
            $("#errorBotonBuscarDireccionEvento").text("");
        }
    });

//    $("#municipio").change(function () {
//        inder.evento.actualizarBarrios();
//    });
    var barrio_selector = $("#barrio");
    barrio_selector.html('');

    $('#tieneReservaPuntoAtencion').hide();
    $('#evento_tieneReserva').on('ifClicked', function (event) {
        var ischecked = event.target.checked;
        if (!ischecked) {
            $('#tieneReservaPuntoAtencion').show();
        } else {
            $('#tieneReservaPuntoAtencion').hide();
        }
    });

    //------------ Roles -------------------------------
    $('input.seleccion-lugar-oferta').on('ifChecked', function (event) {
        inder.evento.seleccionEscenarioOPuntoAtencion();
    });
    //-------------------------------------------------

    //------------ Jugador Evento -------------------------------
    var inputNumeroDocumento = $('.numeroDocumento');

    if (inputNumeroDocumento[1] != undefined) {
        inputNumeroDocumento = inputNumeroDocumento[1];
        inputNumeroDocumento = inputNumeroDocumento.id
        inputNumeroDocumento = $("#" + inputNumeroDocumento);
        inputNumeroDocumento.html('');
    }
    //-------------------------------------------------


    //------------------------ Cupo -----------
    $('#cupo input[type=radio]').on('ifChecked', function () {
        if (this.value == "Individual") {
            $('#campos_equipo').hide();
            $('#campos_individual').show();
        } else if (this.value == "Equipos") {
            $('#campos_equipo').show();
            $('#campos_individual').hide();
        }
    });
    if ($('#campos_equipo').attr('data-cupo') == "Equipos") {
        $('#campos_equipo').show();
        $('#campos_individual').hide()
    } else {
        $('#campos_equipo').hide();
        $('#campos_individual').show();
    }


    //------------------------ Cupo -----------
    $('#evento_personaporsexo').on('ifClicked', function (event) {
        var ischecked = event.target.checked;
        if (!ischecked) {
            $('#personaporsexo').show();
        } else {
            $('#personaporsexo').hide();
        }
    });
    if ($('#personaporsexo').attr('data-personaporsexo') == false) {
        $('#personaporsexo').hide();
    } else if ($('#personaporsexo').attr('data-personaporsexo') == true) {
        $('#personaporsexo').show();
    }

    $('#evento_limitanteporedad').on('ifClicked', function (event) {
        var ischecked = event.target.checked;
        if (!ischecked) {
            $('#limitanteporedad').show();
        } else {
            $('#limitanteporedad').hide();
        }
    });
    if ($('#limitanteporedad').attr('data-limitanteporedad') == false) {
        $('#limitanteporedad').hide();
    } else if ($('#limitanteporedad').attr('data-limitanteporedad') == true) {
        $('#limitanteporedad').show();
    }

    //------------------------ formulario para sanciones -----------
    $('#borrarSancion').on('click', function ()
    {
        if ($(".borrador").is(':checked')) {
            let valor = $('input[name="algo"]:checked').val();
            var url = Routing.generate('ajax_borrar_sancionEvento');
            var data = {
                id: valor,
            }
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    if (data != 0) {
                        location.reload();
                    }
                },
            });

        } else {
            alert("Seleciona alguno para borrar");
        }

    });

    $('.view_link').on('click', function ()
    {
        let valor = $(this).attr('data-idpost');
        var url = Routing.generate('ajax_mostrar_sancionEvento');
        var data = {
            id: valor,
        }
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                if (data != 0) {
                    document.getElementById("modalNombre").innerHTML = data.nombre;
                    document.getElementById("modalDescripcion").innerHTML = data.descripcion;
                    document.getElementById("modalTipo").innerHTML = data.tipo;
                    document.getElementById("modalPuntaje").innerHTML = data.puntaje;

                }
            },
        });
    });

    $('#sanciones_seleccion input[type=radio]').on('ifChecked', function () {

        if (this.value == "Existente") {

            $('#existente').show();
            $('#nueva').hide();
            $('#nueva2').hide();
            $('#nueva3').hide();

        } else if (this.value == "Nuevo") {

            $('#nueva').show();
            $('#nueva2').show();
            $('#nueva3').show();
            $('#existente').hide();
        }
    });

    if ($('#sanciones_seleccion input[type=radio]:checked').val() == "Existente")
    {
        $('#existente').show();
        $('#nueva').hide();
        $('#nueva2').hide();
        $('#nueva3').hide();
    }

    $('.tab-item').on('click', function (e) {
        window.location.replace($(this).attr('href'));
        e.preventDefault();
    });
});