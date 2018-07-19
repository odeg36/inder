    $(document).ready(function () {
    $(function() {
        if($('#dispo1').length <= 0){
            return true
        }
        $('#dispo1').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        }, 
        function(start, end, label) {
            var years = moment().diff(start, 'years');
            
        });
    });

    $(function() {
        if($('#dispo2').length <= 0){
            return true
        }
        $('#dispo2').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        }, 
        function(start, end, label) {
            var years = moment().diff(start, 'years');
            
        });
    });


    $('#time2').on('change', function() {
        
        document.getElementById("alerta").style.display = "none";
        document.getElementById("btnguardar").style.display = "block";
        document.getElementById("alertaTiempo").style.display = "none";

        var fecha1 = $('#dispo1').val()+" "+" "+$('#time1').val();
        var fecha2 = $('#dispo2').val()+" "+" "+$('#time2').val();
        
        
        var reserv = new Date(fecha1);

        var reserv2 = new Date(fecha2);
        
        if(reserv.getTime() === reserv2.getTime()){
            alert("son la misma hora");
        }

        if(reserv.getTime() >= reserv2.getTime()) {
            document.getElementById("alerta").style.display = "block";
            document.getElementById("btnguardar").style.display = "none";
        }

    });

    //click para disponibilidad

    $('.dispo').click(function(){
        
        var fecha1 = $('#dispo1').val();
        var fecha2 = $('#dispo2').val();

        var tiempo1 = $('#time1').val();
        var tiempo2 = $('#time2').val();

        //comparacion para mostrar error al usuario

        if(fecha1 =='' || fecha2 =='' || tiempo1 =='' || tiempo2 =='')
        {
            document.getElementById("alertaTiempo").style.display = "block";

        }else{

            //si esta todo correcto pasamos a consultar las reservas
            document.getElementById("alertaTiempo").style.display = "none";
            //obtenemos el id del escenario deportivo para consultar reservas
            inder.oferta.consultarReservasCalendario($("#escenario_deportivo").val());

            
        }

    });
});