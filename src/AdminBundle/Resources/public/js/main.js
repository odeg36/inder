$(document).ready(function () {
    var imgA1 = $("#img-animacion1"),
            icono1 = $("#icono-otros1"),
            icono2 = $("#icono-otros2"),
            icono3 = $("#icono-otros3"),
            linea1 = $("#linea1"),
            linea2 = $("#linea2"),
            circulo = $("#circulo"),
            circulo2 = $("#circulo2"),
            tl = new TimelineMax();
    tl
            .from(imgA1, .7, {y: -100, opacity: 0, ease: Power2.easeIn})
            .from(icono1, 0.35, {x: 25, opacity: 0, ease: Power2.easeIn})
            .from(icono2, 0.35, {x: 25, opacity: 0, ease: Power2.easeIn})
            .from(icono3, 0.35, {x: 25, opacity: 0, ease: Power2.easeIn});

    tl
            .from(linea1, 1, {scaleY: 0, transformOrigin: "0% 0%", ease: Power2.easeIn}, '-=2.1')
            .from(circulo, 0.35, {opacity: 0, ease: Power2.easeIn}, '-=1.3')
            .from(linea2, 0.5, {scaleY: 0, transformOrigin: "0% 0%", ease: Power2.easeIn}, '-=2.1')
            .from(circulo2, 0.35, {opacity: 0, ease: Power2.easeIn}, '-=1.7');

    var altoHermano = $("#formatoAlto").css("height");
    var altoHermano2 = $("#formatoAlto2").css("height");
    $("#varAlto").css("height", altoHermano);
    $("#varAlto2").css("height", altoHermano2);
});

$(document).on('click', '.mega-dropdown', function (e) {
    e.stopPropagation();
})

/*$('.mega-dropdown').on('click', function(){
 $('.open').on('load', function(){
 console.log($('#seccion-menu').height());
 });
 });*/

$(".mostrar-mas a").on("click", function () {
    var $this = $(this);
    var $content = $this.parent().prev("ul.contenido-colapsable");
    var linkText = $this.text().toUpperCase();

    if (linkText === "MOSTRAR MÁS") {
        linkText = "Ocultar";
        $content.switchClass("ocultar-contenido", "mostrar-contenido", 300);
    } else {
        linkText = "Mostrar más";
        $content.switchClass("mostrar-contenido", "ocultar-contenido", 300);
    }
    ;

    $this.text(linkText);
});

