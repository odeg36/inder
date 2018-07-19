'use strict';

inder.jugadorEvento = {
    crearCarne: function (id) {

        var url = Routing.generate('crear_carne_evento_jugador');
        $.ajax({
            type: 'post',
            url: url,
            data: {id: id},
            beforeSend: function (xhr) {
                $(".cargando-carne-" + id).show();
                $("a.descarga-carne-" + id).hide();
                $("div.img-carne-" + id).html("");
            },
            success: function (object, jqXHR, objectStatus) {
                if (objectStatus.status == 200) {
                    $("div.img-carne-" + id).html(object.html);
                    $("a.descarga-carne-" + id).attr("href", object.file);
                    $("a.descarga-carne-" + id).show();
                } else {
                    $("div.img-carne-" + id).html('mensaje de error');
                }
            },
            complete: function (jqXHR, textStatus) {
                $(".cargando-carne-" + id).hide();
            }
        });
    },

    mostrarCarne: function (id) {
        var url = Routing.generate('mostrar_carne_evento_jugador');
        $.ajax({
            type: 'post',
            url: url,
            data: {id: id},
            success: function (object, jqXHR, objectStatus) {
                if (objectStatus.status == 200) {

                    $("#carneCedula").empty();
                    $("#carneEquipo").empty();
                    $("#carneNombre").empty();
                    $("#carneFecha").empty();
                    $("#carneEvento").empty();
                    $("#carneDisciplina").empty();
                    $("#carneImagen").empty();

                    let cc = object.cc;
                    let equipo = object.equipo;
                    let nombre = object.nombre;
                    let fecha = object.fecha;
                    let evento = object.evento;
                    let disciplina = object.disciplina;
                    let imagen = object.imagen;

                    $("#carneCedula").html(cc);
                    $("#carneEquipo").html(equipo);
                    $("#carneNombre").html(nombre);
                    $("#carneFecha").html(fecha);
                    $("#carneEvento").html(evento);
                    $("#carneDisciplina").html(disciplina);

                    document.getElementById("carneImagen").src = "/uploads/" + imagen;
                } else {
                }
            },

        });
    },

};
$(document).ready(function () {
    $(".numeroIdentificacionJugadorEvento").on('keypress', function (datoSelect) {
        var idTipoDocumento = $(".tipoDocumentoJugadorEvento");
        idTipoDocumento = idTipoDocumento[1];
        idTipoDocumento = $(idTipoDocumento).val();
        var numeroDocumento = $(".numeroIdentificacionJugadorEvento").val();
        var url = Routing.generate('ajax_usuario_por_numero_tipo_documento_autocomplete');
        var data = {idTipoDocumento: idTipoDocumento, numeroDocumento: numeroDocumento}
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var inputNumeroDocumento = $(".numeroIdentificacionJugadorEvento");
                inputNumeroDocumento.autocomplete({
                    source: data.resultado,
                    minLength: 1
                });
            }
        });
    });
});

