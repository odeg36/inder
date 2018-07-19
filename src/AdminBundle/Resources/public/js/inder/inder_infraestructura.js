'use strict';
inder.infraestructura = {
    categoriaImportanciaRelativa: 0,
    categoriaCalificacionGeneral: 0,
    mostrarCamposInfraestructura: function (datoSelect) {
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
            sub_categoria_id: $(datoSelect).val(),
            escenarioSubCategoria: escenarioSubCategoria,
            escenarioCategoria: escenarioCategoria,
            escenarioSubcategoriaEntity: escenarioSubcategoriaEntity,
            campos: campos
        };
        
        var url = Routing.generate('ajax_escenario_deportivo_mostrar_campos_infraestructura');
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
        $('#escenario_deportivo_escenarioCategoriaInfraestructuras_'+id+'_categoriaInfraestructura').select2();
        $('#escenario_deportivo_categoriaInfraestructura_'+id+'_subCategoriaInfraestructura_'+id+'_campoSubCategoriaInfraestructura').select2();
        $('#escenario_deportivo_categoriaInfraestructura_'+id+'_campoCategoriaInfraestructura').select2();
    },
    setSelect2ByClass: function(divElement, element){
        var idSelectElement = element;

        var divCategoria = $('#escenario_deportivo_escenarioCategoriaInfraestructuras_'+divElement+'_categoriaInfraestructura');
        
        var selectCategoria = $('#'+divCategoria[0].id);
        var idCategoria = selectCategoria.val();
        divCategoria = $('#form_categoria_infraestructura_'+divElement);

        var namediv =  $(divCategoria).find('select#'+idSelectElement); // $(datoSelect).attr('data-name');
        $('#'+idSelectElement).select2();
        namediv.html('');
        namediv.select2();

        if (idCategoria != '') {

            var data = {categoriaInfraestructura: idCategoria };
            
            var url = Routing.generate('subcategoria_infraestructura_por_categoria_id');
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
    },
    calculaValor: function(elemento){
        var valor = $(elemento).val();
        if(!valor){
            return 0;
        }
        
        return valor;
    },
    calcularCalificacion: function(){
        var calcularCategorias = $("div.categoria-calcular");
        $(calcularCategorias).each(function(key, item){
            var importanciaRelativas = $(item).find("select.importancia-relativa");
            
            var valorCategoria = 0;
            $(importanciaRelativas).each(function(key, select){
                var respuesta = inder.infraestructura.calculaValor(select);
                if(respuesta){
                    valorCategoria = valorCategoria + parseInt(respuesta);
                    $(item).find("input.importancia-relativa").val(valorCategoria);
                }
            });
            $(importanciaRelativas).each(function(key, select){
                var id = $(select).attr("data-id");
                var respuesta = inder.infraestructura.calculaValor(select);
                if(respuesta){
                    var porcentaje = Math.round((respuesta/valorCategoria) * 100);
                    $(item).find("span.calificacion-porcentaje-" + id).text(porcentaje + "%");
                }
            });
        });
        
        $(calcularCategorias).each(function(key, item){
            var calificacionGenerales = $(item).find("select.calificacion-general");
            var porcentaje = 0;
            $(calificacionGenerales).each(function(key, select){
                var id = $(select).attr("data-id");
                var respuesta = inder.infraestructura.calculaValor(select);
                if(respuesta){
                    var porcentajeItem = $(item).find("span.calificacion-porcentaje-" + id).text();
                    porcentaje += respuesta * parseInt(porcentajeItem);
                }
            });
            
            porcentaje = Math.round(porcentaje/5);
            $(item).find("span.calificacion-general").text(porcentaje + "%");
            $(item).find("input.calificacion-general").val(porcentaje);
        });
        
        inder.infraestructura.calcularVaribaleAuxiliarGeneral();
    },
    calcularVaribaleAuxiliarGeneral: function(){
        var importanciaRelativa = 0;
        $("select.categoria-select option:selected").each(function(key, item){
            if($(item).attr("importancia-relativa")){
                importanciaRelativa += parseInt($(item).attr("importancia-relativa"));
            }
        });
        
        if(importanciaRelativa > 100){
            importanciaRelativa = 100;
        }
        
        if(importanciaRelativa <= 0){
            $("span.variable-auxiliar-general").parents("div.variable-general").hide();
            return true;
        }
        
        $("span.variable-auxiliar-general").parents("div.variable-general").show();
        $("span.variable-auxiliar-general").text(importanciaRelativa + "%");
    }
};
$(document).ready(function () {
    var categorias = $("select.categoria-select");
    
    $(categorias).each(function(key, item){
        inder.escenarioDeportivo.changeCategoriaInfraestructura($(item));
    });
    
    var subCategorias = $("select.subcategoria-select");
    $(subCategorias).each(function(key, item){
        inder.infraestructura.mostrarCamposInfraestructura($(item));
    });
    
    inder.infraestructura.calcularCalificacion();
    inder.infraestructura.calcularVaribaleAuxiliarGeneral();
});

