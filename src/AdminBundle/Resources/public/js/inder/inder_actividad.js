'use strict';
var idFormulario = $('#idFormulario').attr('data-idForm');
inder.actividad = {

    removeActividad: function (e, id){
        $('#form_actividades_'+id).remove();
        $('#delete_'+id).remove();
    },
    
    habilitarBotonEliminar: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.actividades-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    setSelect2: function(id){
        $('#actividad_type_actividades_'+id+'_tipoTiempoEjecucion').select2();
    }
};