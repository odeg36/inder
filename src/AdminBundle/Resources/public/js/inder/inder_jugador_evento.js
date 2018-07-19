'use strict';
inder.jugador = {
    
};

$(document).ready(function () {
    $("#divUsuarioError").hide();
    $("#numeroIdentificacion").keyup(function() {
        
        var tipoDoc = $("#tipoIdentificacion").val();                   
        var url = Routing.generate('ajax_usuario_por_id');
        var numeroIdentificacion = $(this).val();            
        
        if (numeroIdentificacion != '') {            
            var data = {
                numeroIdentificacion: numeroIdentificacion,
                tipoDocumento:tipoDoc
            }            
            $.ajax({
                type: 'post',
                url: url,
                data: data,                
                success: function (data) {
                    if (data.usuario_existe == true) {
                        //$("#"+div+" .nombreUsuario").val(data.resultado[0].nombre);
                        $("#divUsuarioError").hide();
                    }else{
                        
                        $("#divUsuarioError").show();
                    }
                },                
            });
        }
    });
    $("#numeroIdentificacion").change(function() {
        
        
        var numeroIdentificacion = $("#numeroIdentificacion").val();         
        var url = Routing.generate('ajax_usuario_por_id');
        var tipoDoc = $(this).val();
        if (numeroIdentificacion != '') {            
            var data = {
                numeroIdentificacion: numeroIdentificacion,
                tipoDocumento:tipoDoc
            }            
            $.ajax({
                type: 'post',
                url: url,
                data: data,                
                success: function (data) {
                    if (data.usuario_existe == true) {
                        //$("#"+div+" .nombreUsuario").val(data.resultado[0].nombre);
                        $("#divUsuarioError").hide();
                    }else{
                        $("#divUsuarioError").show();                      
                    }
                },                
            });
        }
    });

});