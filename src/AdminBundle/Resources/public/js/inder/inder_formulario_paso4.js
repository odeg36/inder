'use strict';

$(document).ready(function () {
    var $collectionHolder;
    var $addTagLink = $('<a href="#" class="add_tag_link"><i class="ico-anadirdisciplina fa fa-plus-circle fa-2x" aria-hidden="true"></i></a>');
    var $newLinkLi = $('<div class="col-lg-12 listaDocumentos"></div>').append($addTagLink);

    jQuery(document).ready(function () {
        $collectionHolder = $('div.documentos');
        $collectionHolder.find('div').each(function () {
            borrarCampo($(this));
        });
        $collectionHolder.append($newLinkLi);
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagLink.on('click', function (e) {
            e.preventDefault();
            anadirCampo($collectionHolder, $newLinkLi);
        });
    });
    function anadirCampo($collectionHolder, $newLinkLi) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype;

        newForm = newForm.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<div class="col-lg-12 listaDocumentos"></div>').append(newForm);
        $newLinkLi.before($newFormLi);
        borrarCampo($newFormLi);
    }
    function borrarCampo($tagFormLi) {
        var $removeFormA = $('<div class="col-lg-2"><a href="#"></a><i class="ico-quitardisciplina fa fa-minus-circle fa-2x copiar" id="anadir" aria-hidden="true"></i></div>');
        $tagFormLi.append($removeFormA);

        $removeFormA.on('click', function (e) {
            e.preventDefault();
            $tagFormLi.remove();
        });
    }
});