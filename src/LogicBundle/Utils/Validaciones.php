<?php

namespace LogicBundle\Utils;

use LogicBundle\Entity\TipoPersona;
use LogicBundle\Repository\OfertaRepository;

class Validaciones {

    public function urlAccesoOD($usuario) {
        $url = 'sonata_admin_dashboard';

        if ($usuario->getTipoPersona() != TipoPersona::D) {
            return $url;
        }

        if ($usuario->getOrganizacionDeportiva()->getTerminoRegistro() && $usuario->getOrganizacionDeportiva()->getAprobado() == NULL) {
            return 'registroterminado';
        } else if ($usuario->getOrganizacionDeportiva()->getAprobado() && $usuario->getOrganizacionDeportiva()->getTerminoRegistro()) {
            return 'sonata_admin_dashboard';
        }

        if ($usuario->getOrganizacionDeportiva()->getDisciplinaOrganizaciones()->count() <= 0) {
            return $url = 'info_complementaria';
        } elseif ($usuario->getOrganizacionDeportiva()->getOrganismosOrganizacion()->count() <= 0) {
            return 'info_organigrama';
        }

        $deportistas = 0;
        foreach ($usuario->getOrganizacionDeportiva()->getDisciplinaOrganizaciones() as $key => $disciplina) {
            $deportistas += $disciplina->getDeportistas()->count();
        }

        if ($deportistas <= 0) {
            return 'info_deportistas';
        }

        if ($usuario->getOrganizacionDeportiva()->getDocumentos()->count() <= 0) {
            return 'info_documentos';
        }

        if (!$usuario->getOrganizacionDeportiva()->getPeriodoestatutario()) {
            return 'info_pestatutario';
        }

        return $url;
    }

    public function registroAprobado($usuario) {
        if(!$usuario->getOrganizacionDeportiva()){
            return 'sonata_admin_dashboard';
        }
        if ($usuario->getOrganizacionDeportiva()->getTerminoRegistro() && $usuario->getOrganizacionDeportiva()->getAprobado()) {
            return 'sonata_admin_dashboard';
        }

        if ($usuario->getOrganizacionDeportiva()->getTerminoRegistro() && !$usuario->getOrganizacionDeportiva()->getAprobado()) {
            return 'registroterminado';
        }

        if (!$usuario->getOrganizacionDeportiva()->getTerminoRegistro() && !$usuario->getOrganizacionDeportiva()->getAprobado()) {
            return false;
        }
    }

    public function busquedaDisponibilidad(\LogicBundle\Entity\Oferta $newOferta) {

        $dias = array('','Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
        $fechaInicial = $newOferta->getFechaInicial()->format("Y-m-d");
        $fechaInicial = date($fechaInicial);
        $fechaFinal = $newOferta->getFechaFinal()->format("Y-m-d");
        $arreglo = [];
        while ($fechaInicial <= $fechaFinal) {
            $dia = $dias[date('N', strtotime($fechaInicial))];
            foreach ($newOferta->getProgramacion() as $programacion) {
                if ($dia == $programacion->getDia()->getNombre() && $programacion->getHoraInicial()) {
                    $arreglo[$fechaInicial]['horaInicio'] = $programacion->getHoraInicial();
                    $arreglo[$fechaInicial]['horaFin'] = $programacion->getHoraFinal();
                    $arreglo[$fechaInicial]['dia'] =  $programacion->getDia();
                }
            }
//            $oferta = $em->getRepository('LogicBundle:Oferta')->buscarPorDiaHora($fechaInicial, $hora, $condicionPuntoAtencion);
            $fechaInicial = strtotime('+1 day', strtotime($fechaInicial));
            $fechaInicial = date('Y-m-d', $fechaInicial);
        }
        return $arreglo;
    }

}
