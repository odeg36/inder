'use strict';
var idFormulario = $('#idFormulario').attr('data-idForm');
inder.plan_clase = {

    removeActividad: function (e, id){
        $('#form_actividad_'+id).remove();
        $('#delete_'+id).remove();
    },

    habilitarBotonEliminarActividad: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.actividad-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    removeComponente: function (e, id){
        $('#form_componente_'+id).remove();
        $('#delete_'+id).remove();
    },

    habilitarBotonEliminarComponente: function(){
        $("input.btn_remover").each(function(key, item){
            var div = $(item).parents("div.componente-hijo-eliminar");
            if(!$(item).val()){
                $(div).find("a.hidden").removeClass("hidden");
            }else{
                $(div).find("a.hidden").addClass("hidden");
            }
        });
    },

    actualizarOfertas: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            estrategia_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_ofertas_por_estrategia2');
        var oferta_selector = $("#plan_clase_oferta");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#oferta_label");
            },
            success: function (data) {
                oferta_selector.html('<option value="0">Seleccione una oferta</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    oferta_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                oferta_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#oferta_label");
            }
        });
    },

    actualizarModelos: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            planAnualMetodologico_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_modelos_por_planAnualMetodologico');
        var modelo_selector = $("#plan_clase_modelo");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#modelo_label");
            },
            success: function (data) {
                modelo_selector.html('<option value="0">Seleccione un modelo</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    modelo_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                modelo_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#modelo_label");
            }
        });
    },

    actualizarComponentes: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            planAnualMetodologico_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_componentes_por_planAnualMetodologico');
        var componente_selector = $("#plan_clase_componente");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#componente_label");
            },
            success: function (data) {
                componente_selector.html('<option value="0">Seleccione un componente</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    componente_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                componente_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#componente_label");
            }
        });
    },

    actualizarComponentes2: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            planAnualMetodologico_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_componentes_por_planAnualMetodologico');
        var componente_selector = $("#plan_clase_componente2");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#componente2_label");
            },
            success: function (data) {
                componente_selector.html('<option value="0">Seleccione un componente</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    componente_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                componente_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#componente2_label");
            }
        });
    },

    actualizarContenidos: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            componente_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_contenidos_por_componente');
        var contenido_selector = $("#contenidoPlanClasesTecnico");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#contenido_label");
            },
            success: function (data) {
                contenido_selector.html('<option value="0">Seleccione un contenido</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    contenido_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                contenido_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#contenido_label");
            }
        });
    },

    actualizarContenidos2: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            componente_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_contenidos_por_componente');
        var contenido_selector = $("#contenidoPlanClasesSocial");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#contenido2_label");
            },
            success: function (data) {
                contenido_selector.html('<option value="0">Seleccione un contenido</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    contenido_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                contenido_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#contenido2_label");
            }
        });
    },

    actualizarActividades: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            contenido_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_actividades_por_contenido');
        var actividad_selector = $("select.select-actividadtecnico");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#actividad_label");
            },
            success: function (data) {
                actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                actividad_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#actividad_label");
            }
        });
    },

    actualizarActividades2: function (datoSelect) {
        var idFormulario = $('#idFormulario').attr('data-idForm');
        var data = {
            contenido_id: $(datoSelect).val()
        };
        var url = Routing.generate('ajax_actividades_por_contenido');
        var actividad_selector = $("select.select-actividadsocial");
        $.ajax({
            type: 'post',
            url: url,
            data: data,
            beforeSend: function (xhr) {
                inder.agregarCargandoACampo("#actividad2_label");
            },
            success: function (data) {
                actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                for (var i = 0, total = data.length; i < total; i++) {
                    actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
                actividad_selector.change();
            },
            complete: function (jqXHR, textStatus) {
                inder.quitarCargandoACampo("#actividad2_label");
            }
        });
    },

    setSelect1Tecnico: function(id){
        var idContenido = $("#contenidoPlanClasesTecnico").val();
        var selector = $("#plan_clase_actividades_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades_'+id+'_actividad').select2();
    },

    setSelect2Tecnico: function(id){
        var idContenido = $("#contenidoPlanClasesTecnico").val();
        var selector = $("#plan_clase_actividades2_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades2_'+id+'_actividad').select2();
    },

    setSelect3Tecnico: function(id){
        var idContenido = $("#contenidoPlanClasesTecnico").val();
        var selector = $("#plan_clase_actividades3_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades3_'+id+'_actividad').select2();
    },

    setSelect1Social: function(id){
        var idContenido = $("#contenidoPlanClasesSocial").val();
        var selector = $("#plan_clase_actividades4_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad2_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad2_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades4_'+id+'_actividad').select2();
        $('#plan_clase_actividades4_'+id+'_claveObservacion').select2();
    },

    setSelect2Social: function(id){
        var idContenido = $("#contenidoPlanClasesSocial").val();
        var selector = $("#plan_clase_actividades5_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad2_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad2_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades5_'+id+'_actividad').select2();
        $('#plan_clase_actividades5_'+id+'_claveObservacion').select2();
    },

    setSelect3Social: function(id){
        var idContenido = $("#contenidoPlanClasesSocial").val();
        var selector = $("#plan_clase_actividades6_"+id+"_actividad");
        if (idContenido != '') {
            var data = {
                contenido_id: idContenido
            };
            var url = Routing.generate('ajax_actividades_por_contenido');
            var actividad_selector = selector;
            $.ajax({
                type: 'post',
                url: url,
                data: data,
                beforeSend: function (xhr) {
                    inder.agregarCargandoACampo("#actividad2_label");
                },
                success: function (data) {
                    actividad_selector.html('<option value="0">Seleccione una actividad</option>');
                    for (var i = 0, total = data.length; i < total; i++) {
                        actividad_selector.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                    }
                    actividad_selector.change();
                },
                complete: function (jqXHR, textStatus) {
                    inder.quitarCargandoACampo("#actividad2_label");
                }
            });    
        }else{
            selector.html('<option value="0">Seleccione una actividad</option>');
        }
        $('#plan_clase_actividades6_'+id+'_actividad').select2();
        $('#plan_clase_actividades6_'+id+'_claveObservacion').select2();
    },

    setSelect2: function(id){
        $('#kit_territorial_componentes_'+id+'_componente').select2();
    }
}

$(document).ready(function () {


});