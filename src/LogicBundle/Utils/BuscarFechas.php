<?php

namespace LogicBundle\Utils;

use DateInterval;
use DatePeriod;

class BuscarFechas {
    
    public function todasLosDias($inicio, $fin) {
        $fin =  $fin->modify('+1 day');

        $intervalo = new DateInterval('P1D');
        $rangoFechas = new DatePeriod($inicio, $intervalo, $fin);
        $fechas = [];
        foreach ($rangoFechas as $fecha) {
            array_push($fechas, $fecha->format("Y-m-d"));
        }

        return $fechas;
    }
    
    public function tenerDias($fechas = [], $dia = 0) {
        $respuesta = [];
        foreach ($fechas as $fecha) {
            $dayofweek = date('w', strtotime($fecha));
            if ($dayofweek == $dia) {
                array_push($respuesta, $fecha);
            }
        }
        
        return $respuesta;
    }

    public function todosLosDiasxD($inicio, $fin) {
        $fin =  $fin->modify('+0 day');

        $intervalo = new DateInterval('P1D');
        $rangoFechas = new DatePeriod($inicio, $intervalo, $fin);
        $fechas = [];
        foreach ($rangoFechas as $fecha) {
            array_push($fechas, $fecha->format("Y-m-d"));
        }

        return $fechas;
    }
    
}