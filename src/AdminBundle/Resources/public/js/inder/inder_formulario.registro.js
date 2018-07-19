'use strict';

inder.formulario.registro = {
    vereda_titulo: 'Vereda',
    barrio_titulo: 'Barrio',
    direccion: 'direccion',
    comuna: 'comuna',
    barrio: 'barrio',
    direccionTipo: function (elemento, limpiar) {
        var opcion = $(elemento).attr("choice-key");
        var vereda = false;
        if (inder.formulario.registro.direccion == opcion) {
            $("div." + inder.formulario.registro.barrio).find('label').html(inder.formulario.registro.barrio_titulo);
            $("div." + inder.formulario.registro.comuna).addClass("hidden");
            if (limpiar == true) {
                $("select.comuna").val("").change();
            }
        } else if (inder.formulario.registro.comuna == opcion) {
            $("div." + inder.formulario.registro.barrio).find('label').html(inder.formulario.registro.vereda_titulo);
            $("div." + inder.formulario.registro.direccion).addClass("hidden");
            if (limpiar == true) {
                $("select.direccion").val("").change();
            }
            vereda = true;
        }

        $("div." + opcion).removeClass("hidden");
        $("div.puntoAtencion").removeClass("hidden");
        $("div." + inder.formulario.registro.barrio).removeClass("hidden");
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var municipio_selector = $("#municipio");
        var data = {
            municipio_id: municipio_selector.val(),
            vereda: vereda
        };
        var url = Routing.generate('ajax_barrios_por_municipio');
        var barrio_selector = $("#" + idFormulario + "barrio");
        barrio_selector.html('<option value="0">Seleccione una opción</option>');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione una opción</option>');
                for (var i = 0, total = data.barrios.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data.barrios[i].id + '">' + data.barrios[i].nombre + '</option>');
                }
                barrio_selector.change();
                if (data.preguntar_vereda) {
                    $('.direccionOComuna').removeClass('hidden');
                } else {
                    $('.direccionOComuna').addClass('hidden');
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    }
};

$(document).ready(function () {
    $('input.choice-direcion-type').on('ifChecked', function (event) {
        inder.formulario.registro.direccionTipo(event.target, true);
    });

//    $('input.choice-direcion-type').each(function (key, item) {
//        if ($(item).prop('checked') === true) {
//            inder.formulario.registro.direccionTipo(item, false);
//        }
//        ;
//    });
});
