'use strict';
inder.reserva = {

    actualizarBarriosEscenarioReserva: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_municipio');
        var barrio_selector = $("#escenario_deportivo_barrio");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_escenario");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione un barrio</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_escenario");
            }
        });
    },

    actualizarBarriosEscenarioReservaPasoUno: function (escenario_id) {
        var url = Routing.generate('ajax_barrios_por_municipio_medellin');
        var barrio_selector = $("#escenario_deportivo_barrio");
        var barrio_selector_val = $("#escenario_deportivo_barrio").val();

        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-1").text('');
        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-1").text('Seleccione un barrio');
        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-6").text('');
        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-6").text('Seleccione un barrio');
        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-7").text('');
        $(".divBarrioEscenarioDeportivoReserva #select2-chosen-7").text('Seleccione un barrio');
        barrio_selector.prepend('<option value="">Seleccione un barrio</option>');

        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-1").text('Seleccione un barrio');
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-6").text('Seleccione un barrio');
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-7").text('Seleccione un barrio');

                barrio_selector.html('');
                barrio_selector.html('<option value="">Seleccione un barrio</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    if (data[i].id == barrio_selector_val) {
                        barrio_selector.select2('data', {id: data[i].id, text: data[i].nombre, full: true});
                        barrio_selector.append('<option value="' + data[i].id + '" selected >' + data[i].nombre + '</option>');
                    } else {
                        barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                }
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-1").select2();
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-6").select2();
                $(".divBarrioEscenarioDeportivoReserva #select2-chosen-7").select2();
                if (!escenario_id) {
                    inder.reserva.actualizarEscenario($("#escenario_deportivo_barrio"));
                }
                $('.seleccion_tendencia_disciplina input').each(function () {
                    $(this).iCheck('uncheck');
                    $('.disciplinaCaja').hide();
                    $('.tendenciaCaja').hide();
                });
            }
        });
    },

    actualizarEscenario: function (datoSelect) {
        var data = {
            barrio_id: $(datoSelect).val()
        };
        if ($(datoSelect).val() == 0) {
            var url = Routing.generate('ajax_todos_escenarios_por_barrio');
        } else {
            var url = Routing.generate('ajax_escenarios_por_barrio');
        }

        if ($(datoSelect).data("identificacion")) {
            var escenario_selector = $(datoSelect).data("identificacion");
            var escenario_selector = $("#" + escenario_selector);
        } else {
            var idFormulario = $('#idFormulario').attr('data-idForm');
            var escenario_selector = $("#" + idFormulario + "escenarioDeportivo");
        }
        var escenario = $("#escenario_deportivo").val();

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_escenario");
            },
            success: function (data) {
                if (!escenario_selector.hasClass('escenario_oferta')) {
                    escenario_selector.html('<option value="">Seleccione un escenario</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        if (parseInt(escenario) == parseInt(data[i].id)) {
                            escenario_selector.select2('data', {id: data[i].id, text: data[i].nombre, full: true});
                            escenario_selector.append('<option value="' + data[i].id + ' selected ">' + data[i].nombre + '</option>');
                        } else {
                            escenario_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                        }
                    }
                    $('#escenario_deportivo').select2();
                    $('#escenario_deportivo').addClass('form-control');
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_escenario");
            }
        });
        $('.seleccion_tendencia_disciplina input').each(function () {
            $(this).iCheck('uncheck');
            $('.disciplinaCaja').hide();
            $('.tendenciaCaja').hide();
        });
    },

    validarMostrarDivInfo: function (datoSelect, borrar) {
        var escenario_deportivo = $(datoSelect).val();
        $.ajax({
            type: 'post',
            url: Routing.generate('admin_logic_reserva_obtenerInformacionEscenario'),
            data: {
                escenario_id: escenario_deportivo
            },
            success: function (response) {
                $("#show_map").html("");
                $("#show_map").html(response);
                $("#show_map").fadeIn(0);
            }
        });
        if (escenario_deportivo != 0) {

            $('.cajaSeleccionDeporte').show();
            $('#show_map').show();
            $('#reservaDisponible').val('true');
            $('#mostrarInfoEscenario').val('true');

        } else {
            $('.cajaSeleccionDeporte').hide();
            $('#show_map').hide();
            $('#reservaDisponible').val('false');
            $('#mostrarInfoEscenario').val('false');
        }
        $('.seleccion_tendencia_disciplina input[type=radio]').on('ifChecked', function () {
            var data = {
                escenario_id: $('#escenario_deportivo').val()
            };

        });
        $('.seleccion_tendencia_disciplina input').each(function () {
            if (borrar) {
                $(this).iCheck('uncheck');
                $('.disciplinaCaja').hide();
                $('.tendenciaCaja').hide();
            }
        });
    },

    cambioJornada(select) {
        if ($(select).val()) {
            switch (parseInt($(select).val())) {
                case 1:
                    $("div.tarde").each(function () {
                        $(this).fadeOut();
                    });
                    $("div.manana").each(function () {
                        $(this).fadeIn();
                    });
                    break;
                default:
                    $("div.tarde").each(function () {
                        $(this).fadeIn();
                    });
                    $("div.manana").each(function () {
                        $(this).fadeOut();
                    });
                    break;
            }
        }
    },

    asignarNombre: function (datoSelect) {
        var id = $(datoSelect).attr('data-id');
        var url = Routing.generate('ajax_usuario_por_id');
        var data = {
            id: $('#reserva_usuarios_' + id + '_numeroIdentificacion').val()
        }
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo('#reserva_usuarios_' + id + '_firstname');
            },
            success: function (data) {
                var nombre_selector = $('#reserva_usuarios_' + id + '_firstname');
                if (data.usuario_existe == true) {
                    var edad = parseInt(data.resultado[0].edad);
                    var edadMinima = parseInt($("#edadMinima").val());
                    if (edad !== NaN) {
                        if (edad < edadMinima) {
                            nombre_selector.val('El usuario debe ser mayor de ' + edadMinima + ' años para poder usar este servicio');
                            nombre_selector.css({'background-color': '#ffe8ec', 'color': '#f43854'});
                        } else {
                            nombre_selector.val(data.resultado[0].nombre);
                            nombre_selector.css({'background-color': '#eee', 'color': '#000'});
                        }
                    }
                    if (edad < edadMinima) {
                        nombre_selector.addClass("breadcrumb");
                    }

                } else {
                    nombre_selector.val(data.mensaje);
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo('#reserva_usuarios_' + id + '_firstname');
            }
        });
    },

    asignarNoDocumentoReservaPaso1: function (datoSelect) {
        var idTipoDocumento = $("#tipoIdentificacionReservaPaso1").val();
        var numeroDocumento = $("#numeroIdentificacionReservaPaso1").val();
        var url = Routing.generate('ajax_usuario_por_numero_tipo_documento');
        var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento}
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var inputNumeroDocumento = $("#numeroIdentificacionReservaPaso1");
                inputNumeroDocumento.autocomplete({
                    source: data.resultado,
                    minLength: 1
                });
            }
        });
    },

    asignarNoDocumento: function (datoSelect) {
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

    setSelect2: function (id) {
        $('#reserva_usuarios_' + id + '_tipoIdentificacion').select2();
        $('#reserva_usuarios_' + id + '_tipoIdentificacion').attr('data-id', id);
        $('#reserva_usuarios_' + id + '_numeroIdentificacion').attr('data-id', id);
        $('#reserva_usuarios_' + id + '_firstname').attr('disabled', true);
        $('#reserva_usuarios_' + id + '_numeroIdentificacion').on('keyup', function (datoSelect) {
            var index = $(this).attr("data-id");
            var idTipoDocumento = $('#reserva_usuarios_' + index + '_tipoIdentificacion').val();
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
                            var firstname = $('#reserva_usuarios_' + index + '_firstname');
                            firstname.val(data.resultado[0].nombre);
                        }
                    }
                    if (data.query == "") {
                        var firstname = $('#reserva_usuarios_' + index + '_firstname');
                        firstname.val("");
                    }
                }
            });
        });

    },
    pintarDias: function (input, fechaFinal, borrar) {
        var fecha = $(input).val();
        var dia = new Date(fecha).getDay() + 1;
        if (!fechaFinal) {
            var contador = 1;
            while (contador < 8) {
                $("div." + contador).fadeIn(0);
                if (contador != dia) {
                    $("div." + contador).fadeOut(0);
                }
                contador++;
            }
            $("div.programacion input").each(function () {
                if (borrar) {
                    $(this).val("");
                }
            });
        } else {
            var contador = 0;
            var fechaFinal = $(".fechaFin").val();
            var inicio = new Date(fecha).getTime();
            var fin = new Date(fechaFinal).getTime();
            if (inicio <= fin) {
                var diff = (fin - inicio) / (1000 * 60 * 60 * 24);
                if (diff < 7) {
                    var fechaInicio = new Date(fecha);
                    var fechaFin = new Date($(".fechaFin").val());
                    var dias = [];
                    while (fechaInicio <= fechaFin) {
                        dias[contador] = fechaInicio.getDay() + 1;
                        contador++;
                        fechaInicio.setDate(fechaInicio.getDate() + 1);
                    }
                    contador = 1;
                    while (contador < 8) {
                        $("div." + contador).fadeOut(0);
                        contador++;
                    }
                    $.each(dias, function (index, item) {
                        $("div." + item).fadeIn(0);
                    });
                } else {
                    contador = 1;
                    while (contador < 8) {
                        $("div." + contador).fadeIn(0);
                        contador++;
                    }
                }
            }
            $("div.programacion input").each(function () {
                if (borrar) {
                    $(this).val("");
                }
            })
        }
    },
    cancelarReserva() {

        swal({
            title: "¿Está seguro?",
            text: "No podrá continuar con la reserva ni retomarla en otro momento",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, estoy seguro",
        }).then((result) => {
            window.location.href = Routing.generate('admin_logic_reserva_list');
        })
    }
};
$(document).ready(function () {
    if ($(".fechaInicio").val()) {
        if ($(".fechaInicio").attr('solo') == 1) {
            inder.reserva.pintarDias($(".fechaInicio"), false, false);
        } else if ($(".fechaFin").val()) {
            console.log("fecha inicio");
            inder.reserva.pintarDias($(".fechaInicio"), true, false);

        }
    }
    $(".fechaInicio").focusout(function () {
        if ($(this).attr("solo") == 1) {
            inder.reserva.pintarDias(this, false, true);
        } else if ($(".fechaFin").val()) {
            inder.reserva.pintarDias(this, true, true);
        }
    });
    $(".fechaFin").focusout(function () {
        console.log("fecha fin");
        if ($(".fechaInicio").val() && $(".fechaFin").val()) {
            inder.reserva.pintarDias($(".fechaInicio"), true, true);
        }
    });

    inder.reserva.cambioJornada($("#reserva_jornada"));

    if ($('#escenario_deportivo').val()) {
        inder.reserva.validarMostrarDivInfo($('#escenario_deportivo'), false);
        if ($('#tendencia_escenario_deportivoreserva').val() != '') {
            $('.disciplinaCaja').hide();
            $('.tendenciaCaja').show();
        } else if ($('#disciplina_escenario_deportivoreserva').val() != '') {
            $('.disciplinaCaja').show();
            $('.tendenciaCaja').hide();
        }
    } else {
        $('.disciplinaCaja').hide();
        $('.tendenciaCaja').hide();
        $('.cajaSeleccionDeporte').hide();
    }

    $('.seleccion_tendencia_disciplina input[type=radio]').on('ifChecked', function () {
        var data = {
            escenario_id: $('#escenario_deportivo').val()
        };
        if (this.value == "Disciplina") {
            $(".disciplinaCaja").show();
            $(".tendenciaCaja").hide();
            var url = Routing.generate('ajax_disciplinas_por_escenarios');
            var disciplina_selector = $("#disciplina_escenario_deportivoreserva");
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    disciplina_selector.html('');
                    disciplina_selector.append('<option value="">Seleccione una opción</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        disciplina_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    disciplina_selector.change();
                },
            });
        }
        if (this.value == "Tendencia") {
            $(".disciplinaCaja").hide();
            $(".tendenciaCaja").show();
            var url = Routing.generate('ajax_tendencias_por_escenario');
            var tendencia_selector = $("#tendencia_escenario_deportivoreserva");
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    tendencia_selector.html('');
                    for (var i = 0, total = data.length; i < total; i++) {
                        tendencia_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    tendencia_selector.change();
                },
            });
        }
    });


    if ($('#escenario_deportivo').val() != 0) {
        $('.cajaSeleccionDeporte').show();
    } else {
        $('.cajaSeleccionDeporte').hide();
    }
    if ($('.hora_programacion_reserva').val() != null || $('.hora_programacion_reserva') != "") {

    } else {
        $('.hora_programacion_reserva').val('Ingresar hora');
    }
    $('#alertaTiempo').hide();
    $('#alertaTiempoMal').hide();
    $('#reservaDisponible').hide();
    $('.fecha-no-disponilbe').hide();
    $('#alertaTiempoMaxDiv').hide();
    $('.divInfoNoDisponible').hide();
    $('#alertaTiempoMayorMenor').hide();
    $('#alertaMaximodias').hide();
    $('.class-error-disponibilidad').hide();

    $('#error1').hide();
    $('#escenario_deportivo').select2();
    $('#escenario_deportivo').addClass('form-control');
    var i = 0;
    for (i; i < $("#numeroUsuarios").val(); i++) {
        $('#reserva_usuarios_' + i + '_tipoIdentificacion').select2();
        $('#reserva_usuarios_' + i + '_tipoIdentificacion').attr('data-id', i);
        $('#reserva_usuarios_' + i + '_numeroIdentificacion').attr('data-id', i);
        $('#reserva_usuarios_' + i + '_firstname').attr('disabled', true);
    }
    if ($('#mostrarInfoEscenario').val() != 'false') {
        $('#show_map').show();
    } else {
        $('#show_map').hide();
    }
    $('.btnDispo').click(function () {
        var form = {};
        $(".dias-disabled").each(function () {
            $(this).removeAttr("disabled");
        });
        try {
            form = $('#formulario_reserva_paso1').serializeObject();
        } catch (e) {
        }
        $(".dias-disabled").each(function () {
            $(this).attr("disabled", "disabled");
        });

        var url = Routing.generate('admin_logic_reserva_reservasPorEscenarios');
        form = JSON.stringify(form);
        var events = [];
        $.ajax({
            type: 'post',
            url: url,
            data: {
                form: form
            },
            beforeSend: function (xhr) {
                $("div.cargando").show();
            },
            success: function (data) {
                if (data.error) {
                    inder.sweetAlert("Error", data.mensaje, "error");
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar({
                        locale: 'es',
                    });
                } else {
                    events.push({
                        title: '',
                        start: new Date(data.inicio + " 23:00:00"),
                        end: new Date(data.fin + " 23:00:00"),
                        backgroundColor: '#95c11e',
                        borderColor: '#95c11e'
                    });
                    if (data.no_disponibles) {
                        $.each(data.no_disponibles, function () {
                            events.push({
                                title: '',
                                start: new Date(this.inicio + " 23:00:00"),
                                end: new Date(this.fin + " 23:00:00"),
                                backgroundColor: '#d01437', //red
                                borderColor: '#d01437' //red
                            });
                        });
                    }
                    if (data.mantenimientos) {
                        $(data.mantenimientos).each(function () {
                            events.push({
                                title: '',
                                start: new Date(this.inicio + " 23:00:00"),
                                end: new Date(this.fin + " 23:00:00"),
                                backgroundColor: '#f8b934',
                                borderColor: '#f8b934'
                            });
                        });
                    }
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar({
                        locale: 'es',
                    });
                    $("#calendar").fullCalendar('addEventSource', events);
                    $("#calendar").fullCalendar('option', 'aspectRatio', 1);

                    $("#calendar").fullCalendar('gotoDate', data.inicio);
                    $(".fc-time").each(function () {
                        this.remove();
                    });
                }
            },
            complete: function (jqXHR, textStatus) {
                $("div.cargando").hide();
            }
        });
    });

    /**************************************/
    $("#divMensajeReserva").hide();
    $('#btnRechazar').click(function () {
        var data = {
            estadoReserva: 'Rechazado',
            idReserva: $("#idReserva").val(),
            motivoCancelacion: $("#motivo_cancelacion_reserva_motivoCancelacion").val()
        }
        var url = Routing.generate('ajax_rechazar_reserva');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                $("#divMensajeReserva").show();
                $("#textoAprobarReserva").text(data);
                setTimeout(function () {
                    $("#listaReservarRechazar").trigger("click");
                }, 2000);
            },
        });
    });

    $("#motivo_cancelacion_reserva_motivoCancelacion").on('change', function () {
        if ($(this).val() == "") {
            $("#btnRechazar").attr('disabled', true)
            $("#btnRechazar2").attr('disabled', true)
        } else {
            $("#btnRechazar").attr('disabled', false)
            $("#btnRechazar2").attr('disabled', false)
        }
    });

    if ($('#motivo_cancelacion_reserva_motivoCancelacion').val() == '') {
        $('#btnRechazar').prop("disabled", true);
        $('#btnRechazar2').prop("disabled", true);
    } else {
        $('#btnRechazar').prop("disabled", false);
        $('#btnRechazar2').prop("disabled", false);
    }

    $('#btnAprobar').click(function () {
        var data = {
            estadoReserva: 'Aprobado',
            idReserva: $("#idReserva").val(),
        }
        var url = Routing.generate('ajax_aprobar_reserva');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                $("#divMensajeReserva").show();
                $("#textoAprobarReserva").text(data);
                setTimeout(function () {
                    $("#listaReservaAprobar").trigger("click");
                }, 2000);

            },
        });
    });

    $("#listaReservaAprobar").on("click", function (e) {
        window.location.replace($(this).attr('href'));
        e.preventDefault();
    });
    $("#listaReservarRechazar").on("click", function (e) {
        window.location.replace($(this).attr('href'));
        e.preventDefault();
    });
    $('#disciplina').on('change', function () {
        var disciplina_escenario_deportivo = $("#disciplina_escenario_deportivo");
        disciplina_escenario_deportivo.html($('#disciplina option:selected').attr("data-nombre"));
    });
    if ($("#tipoIdentificacionReservaPaso1").length > 0) {
        $("#numeroIdentificacionReservaPaso1").on('keypress', function () {
            var idTipoDocumento = $("#tipoIdentificacionReservaPaso1").val();
            var numeroDocumento = $("#numeroIdentificacionReservaPaso1").val();
            var url = Routing.generate('ajax_usuario_por_numero_tipo_documento');
            var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento}
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    var inputNumeroDocumento = $("#numeroIdentificacionReservaPaso1");
                    inputNumeroDocumento.autocomplete({
                        source: data.resultado,
                        minLength: 1
                    });
                }
            });
        });
    }
    $(".numeroIdentificacionReservaPaso3").on('keypress', function (datoSelect) {
        var idTipoDocumento = $("#tipoIdentificacionReservaPaso1").val();
        var numeroDocumento = $("#numeroIdentificacionReservaPaso1").val();
        var url = Routing.generate('ajax_usuario_por_numero_tipo_documento');
        var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento}
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var inputNumeroDocumento = $("#numeroIdentificacionReservaPaso1");
                inputNumeroDocumento.autocomplete({
                    source: data.resultado,
                    minLength: 1
                });
            }
        });
    });
    var classNumeroIdentificacionReserva = $(".numeroIdentificacionReserva");
    classNumeroIdentificacionReserva.html('');
    //------------------------ Cupo -----------
    $('#tipoReservaPaso1 input[type=radio]').on('ifChecked', function () {
        if ($("#isGestorEscenario").val() == true) {
            var url = Routing.generate('ajax_tipo_reserva');
            var data = {idTipoReserva: this.value}
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    if (data.existeTipoReserva == true) {
                        if (data.resultado == 'Evento' || data.resultado == 'Oferta y Servicio') {
                            $("#divSelectUsuarioReserva").show();
                        } else {
                            $("#divSelectUsuarioReserva").hide();
                        }
                    }
                }
            });
        }
    });
    $('#divAdjuntarDocumentoReserva .fileinput-cancel-button').hide();
    $('#divAdjuntarDocumentoReserva .fileinput-remove-button').hide();
    $('#divAdjuntarDocumentoReserva input[type=file]').on('change', function () {});
    $('#motivoCancelacion2Id').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('href')
        var idReser = button.data('objeto')
        var modal = $(this)
        modal.find('#modalCancelarAction').attr('href', recipient);
        modal.find('#btnRechazar2').attr('data-objeto', idReser);
    });
    $('#btnRechazar2').click(function () {
        var data = {
            idReserva: $("#btnRechazar2").attr('data-objeto'),
            motivoCancelacion: $("#motivo_cancelacion_reserva_motivoCancelacion").val()
        }
        var url = Routing.generate('ajax_agregar_motivo_cancelacion_reserva');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                setTimeout(function () {
                    $("#modalCancelarAction").trigger("click");
                }, 2000);
            },
        });
    });
});
