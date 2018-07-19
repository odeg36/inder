'use strict';
var idFormulario = $('#idFormulario').attr('data-idForm');
inder.escenarioDeportivo = {
    mostrarMapa: function () {
        $('#hide_map').show();
        $('#show_map').hide();
        $('#mapa_escenario').show();
        
    },
    ocultarMapa: function () {
        $('#show_map').show();
        $('#hide_map').hide();
        $('#mapa_escenario').hide();
    },
    restricciones: function (ischecked) {

       
        if(ischecked){
            $('#restricciones_escenario').show();
        }else{
            $('#restricciones_escenario').hide();
        }

        $('#divisionHorarioIgual').on('ifChecked', function () {
            
            if(ischecked){
                $('#restricciones_escenario').show();
            }else{
                $('#restricciones_escenario').show();
            }

        });

    
    },
    actualizarBarriosEscenario: function (datoSelect) {        
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_municipio');
        var barrio_selector = $("#selectBarrioEscenarioDeportivo");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                if (data.preguntar_vereda == true) {
                    $(".divTipoDireccionEscenarioDeportivo").removeClass("hidden");
                    $(".divTipoDireccionEscenarioDeportivo").show();
                    barrio_selector.html('<option value="0">Seleccione un barrio</option>');                    
                    for (var i = 0, total = data.barrios.length; i < total; i++) {                        
                        barrio_selector.append('<option value="' + data.barrios[i].id + '">' + data.barrios[i].nombre + '</option>');
                    }
                    barrio_selector.change();
                }else{
                    $(".divDireccionMunicipio").show();
                    $(".divDireccionMunicipio").removeClass("hidden");
                    $(".divBarrioEscenario").show();
                    $(".divBarrioEscenario").removeClass("hidden");
                    
                    $(".divMunicipioEscenario").show();
                    $(".divMunicipioEscenario").removeClass("hidden");

                    $(".divDireccionComuna").hide();
                    $(".divComunaEscenario").hide();

                    $(".divTipoDireccionEscenarioDeportivo").hide();
                    
                    barrio_selector.html('<option value="0">Seleccione un barrio</option>');
                    
                    for (var i = 0, total = data.barrios.length; i < total; i++) {                        
                        barrio_selector.append('<option value="' + data.barrios[i].id + '">' + data.barrios[i].nombre + '</option>');
                    }
                    barrio_selector.change();
                }
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
    
    removeDivision: function (e, id){
        $('#form_division_'+id).remove();
        $('#delete_'+id).remove();
    },

    habilitarBotonEliminar: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.division-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },
    
    /*removeTipoReserva: function (e, id){
        $('#form_tiposReserva_'+id).remove();
        $('#delete_'+id).remove();
    },

    habilitarBotonEliminarTipoReserva: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.tiposReserva-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },*/
    setSelect2: function(id){        
        $('#escenario_deportivo_divisiones_'+id+'_tipoReserva').select2();
        $('#escenario_deportivo_divisiones_'+id+'_tendencia').select2();
        $('#escenario_deportivo_divisiones_'+id+'_disciplina').select2();
        $('#escenario_deportivo_divisiones_'+id+'_categoriaDivision').select2();
        $('#escenario_deportivo_divisiones_'+id+'_tiposReserva_'+id+'_tipoReserva').select2();

        $('#escenario_deportivo_divisiones_'+id+'_tipoReservaEscenarioDeportivoDivisiones_'+id+'_tipoReserva').select2();        
    },

    setSelect2TipoReserva: function(id){
        $( ".classTipoReservaDivisiones" ).each(function() {
            if ( $(this).is( "select" ) ) {
                $(this).select2();
            }
        });
    },
    changeCategoriaInfraestructura: function(datoSelect){
        var categoriaInfraestructura = $(datoSelect).val();
        var namediv = $(datoSelect).attr('data-name');
        var infoDiv = $('#subcategorias-' +namediv +' .campoDinamico');
        $('#subcategorias-'+namediv +' div.contenido_infraestructura').each(function(k,v){
            $(v).html('');            
        });
        namediv = $('#subcategorias-' +namediv +' select.subcategoria-select');

        var data = {
            categoriaInfraestructura: categoriaInfraestructura
        };

        var url = Routing.generate('subcategoria_infraestructura_por_categoria_id');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var id = $(namediv).val();
                namediv.html('');
                if (data.existe_subcategoria == true) {
                    namediv.append('<option value=""></option>');
                    for (var i = 0; i < data['resultado'].length; i++) {
                        var selected = '';
                        if(id == data['resultado'][i].id){
                            selected = 'selected="selected"';
                        }
                        namediv.append('<option value="' + data['resultado'][i].id + '" '+ selected +'>' + data['resultado'][i].nombre + '</option>');
                    }
                }
            }            
        });
          
    },
    changeCategoriaAmbiental: function(datoSelect){
        var categoriaAmbiental = $(datoSelect).val();
        var namediv = $(datoSelect).attr('data-name');
        
        var infoDiv = $('#subcategorias-' +namediv +' .campoDinamico');
        $('#subcategorias-'+namediv +' div.contenido_ambiental').each(function(k,v){
            $(v).html('');            
        });
        
        namediv = $('#subcategorias-' +namediv +' select.subcategoria-ambiental-select');
        var data = {
            categoriaAmbiental: categoriaAmbiental
        };

        var url = Routing.generate('subcategoria_ambiental_por_categoria_id');
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                var id = $(namediv).val();
                namediv.html('');
                if (data.existe_subcategoria == true) {
                    namediv.append('<option value=""></option>');
                    for (var i = 0; i < data['resultado'].length; i++) {
                        var selected = '';
                        if(id == data['resultado'][i].id){
                            selected = 'selected="selected"';
                        }
                        namediv.append('<option value="' + data['resultado'][i].id + '" '+ selected +'>' + data['resultado'][i].nombre + '</option>');
                    }
                }
               
            }            
        });    
    },
    direccionTipoMunicipio: function(){
        
        
        
        if(inder.formulario.registro.direccion == opcion){
            $("div." + inder.formulario.registro.comuna).addClass("hidden");
            //$("select.barrio").val("").change();
            if(limpiar == true){
                $("select.comuna").val("").change();                
            }
        }else if(inder.formulario.registro.comuna == opcion){
            $("div." + inder.formulario.registro.direccion).addClass("hidden");
            $("#escenario_deportivo_barrio").html('');
            $("#escenario_deportivo_barrio").val("").change();
            $("select.barrio").val("").change();
            if(limpiar == true){                
                $("select.direccion").val("").change();
            }
        }        
        
        $("div." + opcion).removeClass("hidden");
        $("div." + inder.formulario.registro.barrio).removeClass("hidden");
    },
    actualizarComunaBarrios: function (datoSelect) {        
        var data = {
            comuna_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_comuna');
        var barrio_selector = $("#escenario_deportivo_barrio");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione un barrio</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },

    actualizarBarriosPorTipo: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_tipo');
        var barrio_selector = $("#selectBarrioEscenarioDeportivo");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione una vereda</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },

    actualizarBarriosPorTipo2: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            municipio_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_barrios_por_tipo2');
        var barrio_selector = $("#selectBarrioEscenarioDeportivo");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo(".label_barrio");
            },
            success: function (data) {
                barrio_selector.html('<option value="0">Seleccione un barrio</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    barrio_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                barrio_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo(".label_barrio");
            }
        });
    },
};

$(document).ready(function () {    
    $('#show_map').hide();

    $('input').on('ifClicked', function(event){
        inder.escenarioDeportivo.restricciones(event.target.checked);
    });
    
    inder.escenarioDeportivo.restricciones(!$('#escenario_deportivo_tiene_restricciones').is(':checked'));

    if(!$('#acueducto').attr('data-val')){
        $('#acueducto').hide();
    }
    $('#escenario_deportivo_tieneAcueducto').on('ifClicked', function(event){
        var ischecked = event.target.checked;
        if(!ischecked){
            $('#acueducto').show();
        }else{
            $('#acueducto').hide();
        }
    });

    if(!$('#energia').attr('data-val')){
        $('#energia').hide();
    }
    $('#escenario_deportivo_tieneEnergia').on('ifClicked', function(event){
        var ischecked = event.target.checked;
        if(!ischecked){
            $('#energia').show();
        }else{
            $('#energia').hide();
        }
    });

    if(!$('#telefonia').attr('data-val')){
        $('#telefonia').hide();
    }
    $('#escenario_deportivo_tieneTelefono').on('ifClicked', function(event){
        var ischecked = event.target.checked;
        if(!ischecked){
            $('#telefonia').show();
        }else{
            $('#telefonia').hide();
        }
    });

    if(!$('#iluminacion').attr('data-val')){
        $('#iluminacion').hide();
    }
    $('#escenario_deportivo_tieneIluminacion').on('ifClicked', function(event){
        var ischecked = event.target.checked;
        if(!ischecked){
            $('#iluminacion').show();
        }else{
            $('#iluminacion').hide();
        }
    });
    
    if ($("#municipioEscenarioDeportivo").val() != undefined && $("#municipioEscenarioDeportivo").val() != '' && $("#municipioEscenarioDeportivo").val() != ' ') {
        //inder.escenarioDeportivo.actualizarBarriosEscenario($("#municipioEscenarioDeportivo"));
        if ($('#idEscenarioDeportivo').val() != 0 && $('#idEscenarioDeportivo').val() != undefined) {
            var direccion = $('#direccionEscenarioDeportivo').val()        
            var re = /_/g;
            var direccion = direccion.replace(re, ' ');                                
            $("#escenario_deportivo_direccionComuna").val(direccion);    
        }

        if ($('#idEscenarioDeportivo').val() != 0 && $('#idEscenarioDeportivo').val() != undefined) {
            var direccion = $('#direccionEscenarioDeportivo').val()        
            var re = /_/g;
            var direccion = direccion.replace(re, ' ');
            $("#escenario_deportivo_direccionResidencia").val(direccion);                            
        }
    }
    
    
    $('input.choice-escenario-tipo-direccion').on('ifChecked', function (event) {
        inder.escenarioDeportivo.direccionTipo(event.target, true);
    });

    $("#tipoDireccionEscenarioDeportivo input:radio:checked").each(   
        function() {
            $(".divTipoDireccionEscenarioDeportivo").removeClass("hidden");
            $(".divTipoDireccionEscenarioDeportivo").show();
            
            var url = Routing.generate('ajax_tipo_direccion');        
            var data = {idTipoDireccion: $(this).val()}
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                success: function (data) {

                    if (data.usuarios_tipo_direccion == true) {
                        if (data.resultado[0].nombre == "Vereda") {

                            $(".divBarrioEscenario label").text('Vereda');
                            $(".divDireccionComuna").show();
                            $(".divDireccionComuna").removeClass("hidden");
                            $(".divBarrioEscenario").show();
                            $(".divBarrioEscenario").removeClass("hidden");

                            $(".divDireccionComuna").show();
                            $(".divDireccionComuna").removeClass("hidden");
                            $(".divComunaEscenario").show();
                            $(".divComunaEscenario").removeClass("hidden");

                            $(".divDireccionMunicipio").hide();
                            $(".divMunicipioEscenario").hide(); 
                            var classComuna = $(".div_comuna_direccion");                        
                            var item1 = classComuna[1];
                            var item2 = classComuna[2];
                            var item3 = classComuna[3];
                            $(item1).parent().addClass('col-sm-4 col-md-4');
                            $(item2).parent().addClass('col-sm-4 col-md-4');
                            $(item3).parent().addClass('col-sm-4 col-md-4');                                                   
                            
                            if ($('#idEscenarioDeportivo').val() != 0 && $('#idEscenarioDeportivo').val() != undefined) {
                                var direccion = $('#direccionEscenarioDeportivo').val()        
                                var re = /_/g;
                                var direccion = direccion.replace(re, ' ');                                
                                $("#escenario_deportivo_direccionComuna").val(direccion);    
                            }
                            
                        }else if (data.resultado[0].nombre == "Barrio") {
                            $(".divBarrioEscenario label").text('Barrio');
                            $(".divDireccionMunicipio").removeClass("hidden");
                            $(".divBarrioEscenario").removeClass("hidden");
                            $(".divMunicipioEscenario").removeClass("hidden");
                            $(".divDireccionMunicipio").show();
                            $(".divBarrioEscenario").show();
                            $(".divMunicipioEscenario").show();

                            $(".divDireccionComuna").hide();
                            $(".divComunaEscenario").hide();
                            
                            if ($('#idEscenarioDeportivo').val() != 0 && $('#idEscenarioDeportivo').val() != undefined) {
                                var direccion = $('#direccionEscenarioDeportivo').val()        
                                var re = /_/g;
                                var direccion = direccion.replace(re, ' ');
                                $("#escenario_deportivo_direccionResidencia").val(direccion);                            
                            }


                           
                        }
                    }                
                }
            });
        }
    );

    $('#tipoDireccionEscenarioDeportivo input[type=radio]').on('ifChecked', function () {
        var url = Routing.generate('ajax_tipo_direccion');        
        var data = {idTipoDireccion: this.value}
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (data) {
                if (data.usuarios_tipo_direccion == true) {
                    if (data.resultado[0].nombre == "Vereda") {
                        $(".divBarrioEscenario label").text('Vereda');
                        $(".divDireccionComuna").show();
                        $(".divDireccionComuna").removeClass("hidden");
                        $(".divBarrioEscenario").show();
                        $(".divBarrioEscenario").removeClass("hidden");
                        $(".divDireccionMunicipio").hide();

                        $(".divDireccionComuna").show();
                        $(".divDireccionComuna").removeClass("hidden");
                        $(".divComunaEscenario").show();
                        $(".divComunaEscenario").removeClass("hidden");

                        $(".divMunicipioEscenario").hide();
                        var classComuna = $(".div_comuna_direccion");                        
                        var item1 = classComuna[1];
                        var item2 = classComuna[2];
                        var item3 = classComuna[3];
                        $(item1).parent().addClass('col-sm-4 col-md-4');
                        $(item2).parent().addClass('col-sm-4 col-md-4');
                        $(item3).parent().addClass('col-sm-4 col-md-4');

                        inder.escenarioDeportivo.actualizarBarriosPorTipo($("#municipioEscenarioDeportivo"));
                        
                    }else if (data.resultado[0].nombre == "Barrio") {
                        $(".divBarrioEscenario label").text('Barrio');
                        $(".divDireccionMunicipio").show();
                        $(".divDireccionMunicipio").removeClass("hidden");
                        $(".divBarrioEscenario").show();
                        $(".divBarrioEscenario").removeClass("hidden");
                        
                        $(".divMunicipioEscenario").show();
                        $(".divMunicipioEscenario").removeClass("hidden");

                        $(".divDireccionComuna").hide();
                        $(".divComunaEscenario").hide();

                        inder.escenarioDeportivo.actualizarBarriosPorTipo2($("#municipioEscenarioDeportivo"));
                    }
                }                
            }
        });
        
    });

    

    
    $('input.choice-direcion-type-escenario').each(function(key, item){
        if($(item).prop('checked') === true){
            inder.escenarioDeportivo.direccionTipo(item, false);
        };
    });    
    
});