'use strict';

inder.formulario.paso5 = {
    send: false,
    aprobar: function () {
        inder.formulario.paso5.calcularVigencia();
    },
    rechazar: function () {
        $('#organizacion_deportiva_vigencia_calculada').val("");
    },
    calcularVigencia: function () {
        var tiempo = $('#detalle').attr('data-tiempoVigencia');
        var anio = $('#detalle').attr('data-solicitud-anio');
        var mes = $('#detalle').attr('data-solicitud-mes');
        var dia = $('#detalle').attr('data-solicitud-dia');

        var fecha = new Date(parseInt(anio), parseInt(mes) - 1, parseInt(dia));
        var vigencia = inder.formulario.addYears(fecha, tiempo);
        $('#organizacion_deportiva_vigencia').html(vigencia);
        $('#organizacion_deportiva_vigencia_calculada').val(vigencia);
    },
    guardar: function (form) {
        swal({
            title: Translator.trans('formulario_registro.alertas.segurotermina'),
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: Translator.trans('mensaje.registro.btn.si.guardar'),
            cancelButtonText: Translator.trans('mensaje.registro.btn.no')
        }).then(function () {
            inder.formulario.paso5.send = true;

            $(form).trigger('submit');
        }, function (dismiss) {});
    },
    accion: function(elemento){
        var valor = $(elemento).attr("valor").toLowerCase();
        $('.nav-tabs a[href="#tab_'+ valor +'"]').tab('show');
        if(valor == "aprobar"){
            inder.formulario.paso5.aprobar();
        }else{
            inder.formulario.paso5.rechazar();
        }
    }
};
$(document).ready(function () {
    $("form#formulario_disciplina_paso5").submit(function (event) {
        if (!inder.formulario.paso5.send) {
            event.preventDefault();

            inder.formulario.paso5.guardar(event.target);
        }
    });

    $('#btnactualizarSolicitud').click(function (e) {
        var obsTituloalerta = $('#alertas').attr('data-obsTituloalerta');
        var descAlerta = $('#alertas').attr('data-descAlerta');
        var iconoAlerta = $('#alertas').attr('data-iconoAlerta');
        var observaciones = $('#organizacion_deportiva_observaciones').val();
        var estado = $("ul.radioTab li.active a").data("id");
        if (estado == "rechazar" && observaciones == "") {
            e.preventDefault();
            inder.alerta(obsTituloalerta, descAlerta, iconoAlerta);
            return false;
        }
   });
   
   $("input[type='radio'].accion").on('ifClicked', function(event){
        inder.formulario.paso5.accion($(event.target));
    });
});