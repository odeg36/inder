'use strict';
inder.campo = {
    mostrarTipoEntrada: function (datoSelect) {
        if (
            $(datoSelect).val() == "Area de texto"  ||
            $(datoSelect).val() == "Texto"  ||
            $(datoSelect).val() == "Fecha"  ||
            $(datoSelect).val() == ""  ||
            $(datoSelect).val() == "Numero" 
        ) {
            $('.opcionesCampo').hide();
         }else{
            $('.opcionesCampo').show();
        }
    }
};
$(document).ready(function () {
    if (
        $(".opcionesCampo").attr('data-val') == "Area de texto"  ||
        $(".opcionesCampo").attr('data-val')== "Texto"  ||
        $(".opcionesCampo").attr('data-val') == "Fecha"  ||
        $(".opcionesCampo").attr('data-val') == ""  ||
        $(".opcionesCampo").attr('data-val') == "Numero" 
    ) {
        $('.opcionesCampo').hide();
     }else{
        $('.opcionesCampo').show();
    }
    
    
});

