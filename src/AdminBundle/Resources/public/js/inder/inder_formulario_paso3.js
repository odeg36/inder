'use strict';

inder.formulario.paso3 = {
    borrarNuevoDeportista: function (uniqId) {
        var seccion = $('body').find('#nuevoDeporstista-'+uniqId);
        $('body').find(seccion).remove();
        var inputNumeroDocumento = $('body').find('.numero_documento_'+uniqId);
        inputNumeroDocumento.val('').change();
    },
    idNuevoUsuario: '0000',
    idUsuarioNoValido: '00',
    valoresInput: [],
    validadocumento: function (elemento) {
        var existe_usuario = Routing.generate('existe_usuario');
        var tipoidentificacion = $('#tipoidentificacion').val();
        var numeroidentificacion = $('#numeroidentificacion').val();
        var datosusuario = {tipoidentificacion: tipoidentificacion, numeroidentificacion: numeroidentificacion};
        $.ajax({
            url: existe_usuario,
            data: datosusuario,
            method: 'POST',
            dataType: "json",
            success: function (result) {
                if (result.usuario_existe) {
                    alert("existe");
                } else {
                    alert("no existe");
                }
            },
            error: function (err) {

            }
        });
    },
    registrarUsuario: function (id) {
        var tituloalerta = $('#alertas').attr('data-tituloalerta');
        var mensajeerror = $('#alertas').attr('data-mensajeerror');

        var datos = {
            tipoDocumento: $('.nuevo_tipo_documento_' + id).val(),
            numeroDocumento: $('.nuevo_numero_documento_' + id).val(),
            nombresDeportista: $('.nuevo_nombres_deportista_' + id).val(),
            apellidosDeportista: $('.nuevo_apellidos_deportista_' + id).val()
        };

        $.ajax({
            url: Routing.generate("registrar_usuario"),
            data: datos,
            method: 'POST',
            dataType: "json",
            success: function (result) {
                if (result.registro == true) {
                    $('.nombredeportista').fadeIn();
                    $('.nombredeportista').html(nombreDeportista);
                    $('.alertaUsuario').fadeOut(200);
                    $('.usuarioRegistrado').fadeIn(1000);
                    $('.registroUsuario').fadeOut(600);
                }
            },
            error: function (err) {
                inder.alerta(tituloalerta, mensajeerror, "fa fa-sad-o");
            }
        });
    },
    nuevaInformacion: function (elemento, id) {
        var valor = $(elemento).val();

        if ($(elemento).attr("id").search("fake") >= 0) {
            return true;
        }
        
        if (valor === inder.formulario.paso3.idUsuarioNoValido) {
            return true;
        }
        
        if (valor === inder.formulario.paso3.idNuevoUsuario) {
            $.get(Routing.generate('nuevo_deportista'), {"uniqId": id}, function (data) {
                $("div.registro_usuario_" + id).append(data).fadeIn(500);
            });

            $(".nombre_deportista_" + id + " h5").text("");
            return true;
        }

        if (!valor) {
            $("div.registro_usuario_" + id).html("");

            $(".nombre_deportista_" + id + " h5").text("");
            return true;
        }

        $("div.registro_usuario_" + id).fadeOut(500);

        $.post(Routing.generate('existe_usuario_deportista'), {id: valor}, function (data) {
            var nombre = '';
            if (data.firstname) {
                nombre += data.firstname;
            }
            if (data.lastname) {
                nombre += " " + data.lastname;
            }

            $(".nombre_deportista_" + id + " h5").text(nombre);
        });
    },
    cambiaTipoIdentificacion: function (elemento, id) {
        var valor = $(elemento).val();
        if (!valor) {
            valor = 0;
        }

        $.get(Routing.generate('deportista_tipo_identificacion', {"valor": valor, "id": id}), function (data) {});

        $(".numero_documento_" + id).val('').trigger('change');

        $(".nombre_deportista_" + id + " h5").text("");
    },
    cambiarInput: function (uniqId, id) {
        var div = $("div." + uniqId);
        var input = $(div).find("input");
        input.attr("oninput", "inder.formulario.paso3.setInput(this, '" + id + "')");
    },
    setInput: function (elemento, id) {
        var valor = $(elemento).val();

        inder.formulario.paso3.valoresInput[id] = valor;
    },
    setForm: function (id) {
        var tipo = $(".tipo_identificacion_" + id).val();
        $(".nuevo_tipo_documento_" + id).val(tipo).trigger('change');
        $(".nuevo_numero_documento_" + id).val(inder.formulario.paso3.valoresInput[id]);
    }
};
