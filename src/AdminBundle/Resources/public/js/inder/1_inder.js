'use strict';

var inder = {
    agregarCargandoACampo: function (campo) {
        var label = $(campo);
        if (label) {
            label.attr('style', 'float:left;');
            label.after('<span class="loader"></span>');
        }

    },
    quitarCargandoACampo: function (campo) {
        var label = $(campo);
        if (label) {
            label.removeAttr('style');
            label.parent().find('span.loader').remove();
        }

    },
    actualizarComunas: function (datoSelect) {
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_comunas_por_municipio');
        var comuna_selector = $("select.comuna-select");

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_comuna");
            },
            success: function (data) {
                comuna_selector.attr('placeholder', 'Seleccione una opción');
                for (var i = 0, total = data.length; i < total; i++) {
                    comuna_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                comuna_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_comuna");
            }
        });
    },
    alerta: function (titulo, contenido, icono) {
        $.alert({
            title: titulo,
            content: contenido,
            icon: icono,
            autoClose: 'aceptar|2000',
            theme: 'supervan',
            animation: 'scale',
            closeAnimation: 'scale',
            scrollToPreviousElement: false,
            buttons: {
                aceptar: {
                    text: 'Aceptar',
                    btnClass: 'btn-blue'
                }
            }
        });
    },
    sweetAlert: function (titulo, texto, tipo) {
        swal({
            title: titulo,
            text: texto,
            type: tipo,
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonText: Translator.trans('btn.aceptar')
        });
    },
    nuevaFecha: function (fecha, intervalo, dma, simbolo) {
        var simbolo = simbolo || "-";
        var arrayFecha = fecha.split(simbolo);
        var anio = arrayFecha[0];
        var mes = arrayFecha[1];
        var dia = arrayFecha[2];

        var fechaInicial = new Date(anio, mes - 1, dia);
        var fechaFinal = fechaInicial;
        if (dma == "m" || dma == "M") {
            fechaFinal.setMonth(fechaInicial.getMonth() + parseInt(intervalo));
        } else if (dma == "y" || dma == "Y") {
            fechaFinal.setFullYear(fechaInicial.getFullYear() + parseInt(intervalo));
        } else if (dma == "d" || dma == "D") {
            fechaFinal.setDate(fechaInicial.getDate() + parseInt(intervalo));
        } else {
            return fecha;
        }
        dia = fechaFinal.getDate();
        mes = fechaFinal.getMonth() + 1;
        anio = fechaFinal.getFullYear();

        dia = (dia.toString().length == 1) ? "0" + dia.toString() : dia;
        mes = (mes.toString().length == 1) ? "0" + mes.toString() : mes;

        return anio + "-" + mes + "-" + dia;
    },
    fileInput: function () {
        try {
            $('input[type="file]').fileinput({
                language: "es",
                allowedFileExtensions: ["jpg", "png", "gif"]
            });
        } catch (e) {
        }
    },
    fileMask: function () {
        $(".mask").inputmask();
    }
};

$(document).ready(function () {
    inder.fileInput();
    $('select').each(function () {
        var id = $(this).attr('id');
        if (typeof id !== "undefined" && id.indexOf('autocomplete') === -1) {
            $(this).select2({
                placeholder: Translator.trans('seleccionar.opcion'),
                allowClear: true,
                width: '100%'
            });
        }
    });
});