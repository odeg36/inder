'use strict';
inder.preinscripcion = {
    noDefinido: '',
    calcularIMC: function () {
        var peso = $('.peso').val();
        var estatura = $('.estatura').val() / 100;
        var imc = parseFloat(peso / (estatura * estatura)).toFixed(2);
        $('.imc').val(!isNaN(imc) && isFinite(imc) ? imc : 0);
    },
    visibilidadDesplazado: function () {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var checkbox = $('#' + idFormulario + "esDesplazado");
        var campo = $('#campoDesplazado');
        var checked = checkbox.parent('[class*="icheckbox"]').hasClass("checked");
        if (checked) {
            campo.removeClass('ocultar');
            campo.addClass('mostrar');
        } else {
            campo.removeClass('mostrar');
            campo.addClass('ocultar');
            var selectDesplazado = $('#' + idFormulario + "tipoDesplazado");
            selectDesplazado.val('').change();
        }
    },
    visibilidadDiscapacitado: function () {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var checkbox = $('#' + idFormulario + "esDiscapacitado");
        var campo = $('#campoDiscapacitado');
        var checked = checkbox.parent('[class*="icheckbox"]').hasClass("checked");
        if (checked) {
            campo.removeClass('ocultar');
            campo.addClass('mostrar');
        } else {
            campo.removeClass('mostrar');
            campo.addClass('ocultar');
            var selectDiscapacidad = $('#' + idFormulario + "discapacidad");
            selectDiscapacidad.val('');
            var selectSubDiscapacidad = $('#' + idFormulario + "subDiscapacidad");
            selectSubDiscapacidad.val('').change();
        }
    },
    actualizarSubDiscapacidades: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            discapacidad_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_subdiscapacidades_por_discapacidad');
        var subDiscpacidad_selector = $("#" + idFormulario + "subDiscapacidad");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo('.label_subDiscapacidades');
            },
            success: function (data) {
                subDiscpacidad_selector.html('<option value="0">Seleccione un subtipo de discapacidad</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    subDiscpacidad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                subDiscpacidad_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo('.label_subDiscapacidades');
            }
        });
    },
    usuario: function (elemento, id, oferta) {
        var valor = $(elemento).val() ? $(elemento).val() : 0;

        if ($(elemento).attr("id").search("fake") >= 0) {
            return true;
        }

        if (valor != 0) {
            $("div.cargando").show();
            $.get(Routing.generate('admin_logic_preinscripcionoferta_preinscripcionAcompanante', {id: oferta}), {"uniqId": id, usuario: valor}, function (data) {
                $("div.formulario_acompanate_" + id).append(data);
                $("div.cargando").hide();
            });
        }

        $(".nombre_usuario_" + id + " h5").text("");

        if (!valor) {
            $("div.formulario_acompanate_" + id).html("");

            $(".nombre_usuario_" + id + " h5").text("");
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
        });
    },
    usuarioRegistro: function (elemento, id, oferta) {
        var valor = $(elemento).val() ? $(elemento).val() : null;

        if ($(elemento).attr("id").search("fake") >= 0) {
            return true;
        }

        if (valor) {
            $("div.cargando").show();
            $.get(Routing.generate('admin_logic_preinscripcionoferta_cargaUsuarioNuevoPreinscripcion', {id: oferta}), {"uniqId": id, usuario: valor}, function (data) {
                $("div.form-usuario-preinscripcion").html(data);
                $("div.cargando").hide();
            });
        }

        $(".nombre_usuario_" + id + " h5").text("");

        if (!valor) {
            $("div.form-usuario-preinscripcion").html("");

            $(".nombre_usuario_" + id + " h5").text("");
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
        });
    },
    accionesChecbox: function () {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var campo = $('.mostrarDirNueva');
        campo.parent().parent().addClass('ocultar');
        var checkboxDezplazado = $('#' + idFormulario + "esDesplazado");
        checkboxDezplazado.on('ifClicked', function (event) {
            setTimeout(function () {
                inder.preinscripcion.visibilidadDesplazado();
            }, 1);
        });

        var checkboxDiscapacitado = $('#' + idFormulario + "esDiscapacitado");
        checkboxDiscapacitado.on('ifClicked', function (event) {
            setTimeout(function () {
                inder.preinscripcion.visibilidadDiscapacitado();
            }, 1);
        });

        var idSeccionOferta = $('#idsForm').data('oferta');
        $('#' + idSeccionOferta).children().each(function () {
            $(this).hide();
        });
        inder.preinscripcion.visibilidadDesplazado();
        inder.preinscripcion.visibilidadDiscapacitado();
    },
    nivelEscolaridad: function (elemento) {
        var texto = $(elemento).find("option:selected").text();
        if (texto.toLowerCase() == inder.preinscripcion.noDefinido.toLowerCase()) {
            $("select.tipo-establecimiento-educativo").attr("disabled", "disabled");
            $("select.establecimiento-educativo").attr("disabled", "disabled");
        } else {
            $("select.tipo-establecimiento-educativo").removeAttr("disabled");
            $("select.establecimiento-educativo").removeAttr("disabled");
        }
    },
    tipoEstablecimientoEducativo: function (elemento) {
        var texto = $(elemento).find("option:selected").text();
        if (texto.toLowerCase() == inder.preinscripcion.noDefinido.toLowerCase()) {
            $("select.establecimiento-educativo").attr("disabled", "disabled");
        } else {
            $("select.establecimiento-educativo").removeAttr("disabled");
        }
    },
    checkboxMedicamentos: function () {
        $('.checkbox-medicamentos').on('ifClicked', function (ev) {
            var checked = !$(this).prop('checked');
            var areaMedicamentos = $('.area-medicamentos');
            if (checked) {
                areaMedicamentos.removeAttr('disabled');
            } else {
                areaMedicamentos.val('');
                areaMedicamentos.attr('disabled', 'disabled');
            }
        });
    },
    checkboxEnfermedades: function () {
        $('.checkbox-enfermedades').on('ifClicked', function (ev) {
            var checked = !$(this).prop('checked');
            var areaEnfermedades = $('.area-enfermedades');
            if (checked) {
                areaEnfermedades.removeAttr('disabled');
            } else {
                areaEnfermedades.val('');
                areaEnfermedades.attr('disabled', 'disabled');
            }
        });
    },
    preguntaRechazar: function(preInscripcion){
        swal({
            title: Translator.trans('mensaje.preinscripcion.rechazar.titulo') + '\n\n' + Translator.trans('mensaje.preinscripcion.rechazar.aclaracion'),
            text: Translator.trans('mensaje.preinscripcion.rechazar.texto'),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: Translator.trans('mensaje.si'),
            cancelButtonText: Translator.trans('mensaje.no')
        }).then(function () {
            inder.preinscripcion.rechazaPreinscripcion(preInscripcion);
        }, function (dismiss) {});
    },
    rechazaPreinscripcion: function(preInscripcion){
        var url = Routing.generate('admin_logic_preinscripcionoferta_rechazarPreInscripcion');
        $.ajax({
            type: 'get',
            url: url,
            data: {
                id: preInscripcion
            },
            beforeSend: function (xhr) {
                
            },
            success: function (data) {
                window.location = data.url;
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo('.label_subDiscapacidades');
            }
        });
    }
};

$(document).ready(function () {
    inder.preinscripcion.accionesChecbox();
    inder.preinscripcion.checkboxMedicamentos();
    inder.preinscripcion.checkboxEnfermedades();
    if ($("select.nivel-escolaridad").length <= 1) {
        inder.preinscripcion.nivelEscolaridad($("select.nivel-escolaridad"));
        inder.preinscripcion.tipoEstablecimientoEducativo($("select.tipo-establecimiento-educativo"));
    }
});