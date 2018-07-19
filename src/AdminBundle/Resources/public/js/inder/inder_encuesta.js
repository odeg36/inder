'use strict';
inder.encuesta = {

    mostrarEncuesta: function (element) {
        
        var url = Routing.generate('ajax_mostrar_encuesta', {  });
        
        $.ajax({
            type: 'get',
            url: url,           
            success: function (data) {
                if (data.show_encuesta === undefined) {
                    // se ejecutan estas instrucciones
                    var encuestaModal = $('#encuestaModal');
                    encuestaModal.html(data);
                    $("#encuestaModal").modal("show");
                }
                else {}
            }
        });
    },

};
$(document).ready(function () {
    inder.encuesta.mostrarEncuesta();
});

