(function ($) {
    'use strict';
    $.fn.autocompleter = function (options) {
        var settings = {
            url_list: '',
            url_get: '',
            placeholder: '',
            otherOptions: {minimumInputLength: 2}
        };
        return this.each(function () {
            if (options) {
                $.extend(true, settings, options);
            }
            var ndivs = $('body .registroUsuario');
            var $this = $(this);
            var tipoidentificacion = $(this).parent().parent().find('.tipoidentificacion:eq(0)');
            var divRegistro = $(this).parent().parent().find('.registroUsuario');
            var nombredeportista = $(this).parent().find('.nombredeportista');
            var alertaUsuario = $(this).parent().parent().find('.alertaUsuario');
            var a = "";
            $(tipoidentificacion).change(function () {
                a = $(this).val();
            });
            var $fakeInput = $this.clone();
            var val = '';
            var select2options = {
                ajax: {
                    url: settings.url_list,
                    dataType: 'json',
                    delay: 250,
                    placeholder: settings.placeholder,
                    data: function (params) {
                        return {
                            numeroidentificacion: params,
                            tipoidentificacion: a,
                        };
                    },
                    results: function (data) {
                        var results = [];
                        $.each(data, function (index, item) {
                            if (item.id != 0) {
                                results.push({
                                    id: item.id,
                                    text: item.text
                                });
                                //alertaUsuario.fadeOut(500);
                                //divRegistro.fadeOut(500);
                            }
//                            else {
//                                nombredeportista.hide();
//                                if (ndivs.find(".registro:visible").length < 1) {
//                                    alertaUsuario.fadeIn(500);
//                                    divRegistro.fadeIn(500);
//                                } else {
//                                    alertaUsuario.fadeIn(500);
//                                    alertaUsuario.remove();
//                                    divRegistro.remove();
//                                }
//                            }
                        });
                        return {
                            results: results
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                initSelection: function (element, callback) {
                    var data = {id: element.val(), text: val};
                    callback(data);
                }
            };
            $this.removeAttr('required');
            $fakeInput.removeAttr('required');
            if (settings.otherOptions) {
                $.extend(true, select2options, options.otherOptions);
            }
            $fakeInput.attr('id', 'fake_' + $fakeInput.attr('id'));
            $fakeInput.attr('name', 'fake_' + $fakeInput.attr('name'));
            $this.hide().after($fakeInput);
            $fakeInput.select2(select2options);
            if ($this.attr('value')) {
                $.ajax({
                    url: (settings.url_get.substring(-1) === '/' ? settings.url_get : settings.url_get + '/') + $this.attr('value'),
                    success: function (name) {
                        val = name;
                        $fakeInput.select2('val', name);
                    }
                });
            }
            $fakeInput.on('change', function (e) {
                $this.val(e.val).change();
                nombredeportista.html(e.val);
            });
        });
    };
})(jQuery);