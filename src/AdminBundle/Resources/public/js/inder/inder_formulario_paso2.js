'use strict';

inder.formulario.paso2 = {
    cd: "CD",
    texto: "Comisión Disciplinaria",
    activarOpcionesPerfil: function () {
        var selects = $("select.select-tipo-organo");
        $(selects).each(function (key, item) {
            var abreviatura = $(item).find("option:selected").attr("data-abreviatura");
            if (abreviatura == inder.formulario.paso2.cd) {
                var contenedorPadre = $(item).parents("div.contenedor-padre");
                var selects = $(contenedorPadre).find("select.select-perfil");
                $(selects).each(function (key, select) {
                    var options = $(select).find("option");
                    $(options).each(function (key, option) {
                        if ($(option).val()) {
                            if ($(option).text() != inder.formulario.paso2.texto) {
                                $(option).remove();
                            }
                        }
                    });
                });
            }
        });
    }
};

$(document).ready(function () {
    inder.formulario.paso2.activarOpcionesPerfil();
});