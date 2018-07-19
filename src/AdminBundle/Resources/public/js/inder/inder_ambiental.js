'use strict';
inder.ambiental = {
    mostrarCamposAmbiental: function (datoSelect) {
        var campos = $("div.campos-dinamicos").attr("data-json");
        campos = JSON.parse(campos);
        
        var escenarioSubCategoria = $(datoSelect).attr("data-id");
        var padre = $(datoSelect).parents("div.data-id");
        var escenarioCategoria = null;
        if(padre){
            escenarioCategoria = $(padre).attr("data-id");
        }
        
        var escenarioSubcategoriaEntity = $(datoSelect).parents("div.data-escenario-subcategoria");
        if(escenarioSubcategoriaEntity){
            escenarioSubcategoriaEntity = $(escenarioSubcategoriaEntity).attr("data-escenario-subcategoria");
        }
        
        var data={
            categoria_id: $(datoSelect).val(),
            sub_categoria_id: $(datoSelect).val(),
            escenarioSubCategoria: escenarioSubCategoria,
            escenarioCategoria: escenarioCategoria,
            escenarioSubcategoriaEntity: escenarioSubcategoriaEntity,
            campos: campos
        };
        
        var url = Routing.generate('ajax_escenario_deportivo_mostrar_campos_ambiental');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {},
            success: function (data) {
                $(datoSelect).parent().parent().find("div.campos-dinamicos-subcategoria").html('');
                $(datoSelect).parent().parent().find("div.campos-dinamicos-subcategoria").append(data);
                
                inder.fileMask();
                
                $(datoSelect).val($(datoSelect).val());
            },
            complete: function (jqXHR, textStatus) {}
        });
    },
    setSelect2: function(id){ 
       $('#categoria_ambiental_type_subcategoriaAmbientales_'+id+'_campos').select2();
        $('#escenario_deportivo_categoriaAmbiental_'+id+'_campoCategoriaAmbiental').select2();
        $('#escenario_deportivo_categoriaAmbiental_'+id+'_subCategoriaAmbiental_'+id+'_campoSubCategoriaAmbiental').select2();
        $('#escenario_deportivo_escenarioCategoriaAmbientales_'+id+'_categoriaAmbiental').select2();
    },
    setSelect2ByClass: function(divElement, element){
        var idSelectElement = element;

        var divCategoria = $('#escenario_deportivo_escenarioCategoriaAmbientales_'+divElement+'_categoriaAmbiental');
        
        var selectCategoria = $('#'+divCategoria[0].id);
        var idCategoria = selectCategoria.val();
        divCategoria = $('#form_categoria_ambiental_'+divElement);

        var namediv =  $(divCategoria).find('select#'+idSelectElement); // $(datoSelect).attr('data-name');
        $('#'+idSelectElement).select2();
        namediv.html('');
        namediv.select2();

        if (idCategoria != '') {

            var data = {categoriaInfraestructura: idCategoria };
            
            var url = Routing.generate('subcategoria_ambiental_por_categoria_id');
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {
                    namediv.html('');
                    if (data.existe_subcategoria == true) {
                        namediv.append('<option value=""></option>');
                        for (var i = 0; i < data['resultado'].length; i++) {
                            namediv.append('<option value="' + data['resultado'][i].id + '">' + data['resultado'][i].nombre + '</option>');
                        }
                    }
                   
                }            
            });
        }else{
            namediv.html('');            
        }
    }
};
$(document).ready(function () {
    var categorias = $("select.categoria-ambiental-select");
    $(categorias).each(function(key, item){
        inder.escenarioDeportivo.changeCategoriaAmbiental($(item));
    });
    
    var subCategorias = $("select.subcategoria-ambiental-select");
    $(subCategorias).each(function(key, item){
        inder.ambiental.mostrarCamposAmbiental($(item));
    });
});

