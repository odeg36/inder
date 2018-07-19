'use strict';

inder.formulario = {
    N: "N",
    J: "J",
    D: "D",
    uniqId: function () {
        return (new Date().getTime()).toString(36);
    },
    cambiaTipoPersona: function (elemento) {
        var valor = $(elemento).val();

        $('.error_campo_mensaje').fadeOut(400);
        if (valor != inder.formulario.N && valor != inder.formulario.D) {
            $("select#tipoidentificacion option[tipo='NT']").removeClass("hide");
            $('#persona_natural').fadeOut(400);
            $('#persona_juridica').fadeOut(400);
            $('.datos_contacto').fadeOut(400);
            $('.datos_clave').fadeOut(400);
            $(".datos_proteccion").fadeOut(400);
        }

        if (valor == inder.formulario.N) {
            $("select#tipoidentificacion option[tipo='NT']").addClass("hide");
            $('#persona_natural').fadeIn(400);
            $('#persona_juridica').fadeOut(400);
            $('.datos_contacto').fadeIn(400);
            $(".datos_proteccion").fadeIn(400);
            $('.datos_clave').fadeIn(400);
            $("#varAlto").css("height", "306px");
        }

        if (valor == inder.formulario.D) {
            $("select#tipoidentificacion option[TIPO='NT']").removeClass("hide");
            $('#persona_natural').fadeOut(400);
            $('#persona_juridica').fadeIn(400);
            $('.datos_contacto').fadeIn(400);
            $('.datos_clave').fadeIn(400);
            $(".datos_proteccion").fadeIn(400);
            $("#varAlto").css("height", "306px");
        }
        var altoHermano2 = $("#formatoAlto2").css("height");
        $("#varAlto2").css("height", altoHermano2);
    },

    actualizarDireccion: function (elementoAgregarDireccion) {
        if(!elementoAgregarDireccion){
            elementoAgregarDireccion = "";
        }
        $(elementoAgregarDireccion).val("");
        var direccion = "";
        var dato_direccion = $(".dato_direccion");
        dato_direccion.each(function () {
            if (this.value != 'undefined' && this.value != null && this.value != "") {
                if ($(this).hasClass("numero_generador")) {
                    direccion += "# " + this.value + " ";
                } else if ($(this).hasClass("numero_placa")) {
                    direccion += "- " + this.value + " ";
                } else {
                    direccion += this.value + " ";
                }
            }

        });
        if (elementoAgregarDireccion !== "") {
            $(elementoAgregarDireccion).val(direccion.trim());
        } else {
            return direccion.trim();
        }
    },
     actualizarDireccionComuna: function (elementoAgregarDireccion) {
        if(!elementoAgregarDireccion){
            elementoAgregarDireccion = "";
        }
        $(elementoAgregarDireccion).val("");
        var direccion = "";
        var dato_direccion = $(".dato_comuna");

        dato_direccion.each(function () {
            if (this.value != 'undefined' && this.value != null && this.value != "") {
                if ($(this).hasClass("complemento")) {
                    direccion += "Finca " + this.value + " ";
                } else {
                    direccion += this.value + " ";
                }
            }

        });
        if (elementoAgregarDireccion !== "") {
            $(elementoAgregarDireccion).val(direccion.trim());
        } else {
            return direccion.trim();
        }
    },
    eliminarDisableDFormSubmit: function () {
        $("form").submit(function (elemento) {
            var form = elemento.target;
            var inputs = $(form).find("[disabled]");

            $(inputs).removeAttr("disabled");
        });
    },
    validarClave: function () {
        $('#clave_uno').keyup(function () {
            var pswd = $(this).val();
            var seguridad = 0;
            if (pswd.length < 6) {
                $('#length').removeClass('valid').addClass('invalid');
                seguridad++;
            } else {
                $('#length').removeClass('invalid').addClass('valid');
            }
            if (pswd.match(/[a-z]/)) {
                $('#letter').removeClass('invalid').addClass('valid');
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
                seguridad++;
            }
            if (pswd.match(/\d/)) {
                $('#number').removeClass('invalid').addClass('valid');
            } else {
                $('#number').removeClass('valid').addClass('invalid');
                seguridad++;
            }
            event.preventDefault();
        }).focus(function () {
            $('#pswd_info').show();
        }).blur(function () {
            $('#pswd_info').hide();
        });
    },
    redirecHome: function () {
        swal({
            title: Translator.trans('mensaje.registro.titulo'),
            text: Translator.trans('mensaje.registro.texto'),
            type: 'question', showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: Translator.trans('mensaje.registro.btn.si'),
            cancelButtonText: Translator.trans('mensaje.registro.btn.no')
        }).then(function () {
            window.location = Routing.generate("home");
        }, function (dismiss) {});
    }, llenarBarrios: function (municipioId, campoBarrio) {
        var municipio = {
            municipio_id: municipioId};
        var url_barrios = Routing.generate('ajax_barrios_por_municipio');
        $.ajax({
            type: 'post',
            url: url_barrios,
            data: municipio,
            success: function (data) {
                var $barrio_selector = campoBarrio;
                $barrio_selector.html('<option></option>');
                $.each(data, function (key, value) {
                    $barrio_selector.append('<option value="' + value.id + '">' + value.nombre + '</option>');
                });
                $barrio_selector.change();
            }
        });
    },
    buscarUsuario: function () {
        var tipoidentificacion = $('#tipoidentificacion').val();
        var numeroidentificacion = $('#numeroidentificacion').val();
        var datosusuario = {tipoidentificacion: tipoidentificacion, numeroidentificacion: numeroidentificacion};

        $.ajax({
            url: Routing.generate("existe_usuario"),
            data: datosusuario,
            method: 'POST',
            dataType: "json",
            success: function (result) {
                if (result.usuario_existe) {
                    $('#numeroidentificacion').addClass("error_campo");
                    $('#identificacionregistrada').show(500);
                    $('#numeroidentificacion').focus();
                } else {
                    $('#identificacionregistrada').hide(500);
                    $('#numeroidentificacion').removeClass("error_campo");
                }
            },
            error: function (err) {}
        });
    },
    
    eliminarCampo: function(element){
        $(element).parents("tr").remove();
    },
    
    addYears: function(date, years) {
        var dd = date.getDate(); 
        var mm = date.getMonth()+1;
        var yyyy = date.getFullYear()+5; 
        if(dd<10){dd='0'+dd} 
        if(mm<10){mm='0'+mm} 
        return dd+'/'+mm+'/'+yyyy;;
    }
};

$(document).ready(function () {
    inder.formulario.eliminarDisableDFormSubmit();

    var valor = $('#tipopersona').val();
    if (valor) {
        inder.formulario.cambiaTipoPersona($('#tipopersona'));
    }

    inder.formulario.validarClave();

    ////// barrio ////
    $("#barrio").change(function () {
        inder.oferta.actualizarEscenario(this);
    });

    ////// escenario /////
    $("#escenario_deportivo").change(function () {
        inder.oferta.actualizarDisciplina(this);
    });
    
});

     