<?php

//namespace ServicesBundle\Controller;
//
//use FOS\RestBundle\Controller\FOSRestController;
//use FOS\RestBundle\View\View;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Request as Request;
//use ServicesBundle\Tools\ResponseBuilder;
//use Nelmio\ApiDocBundle\Annotation\ApiDoc;
//use FOS\RestBundle\Controller\Annotations\Get;
//
//class EventosController extends FOSRestController {
//
//    /**
//     * Lista de eventos
//     *
//     * @ApiDoc(
//     *  section="Evento",
//     *  resource=true,
//     *  description="Lista de categorias de eventos",
//     *  filters={
//     *      {"name"="pagina", "dataType"="integer"},
//     *      {"name"="elementos_por_pagina", "dataType"="integer"}
//     *  }
//     * )
//     * @Get("/categorias_eventos")
//     */
//    public function getAction(Request $request) {
//        $em = $this->getDoctrine()->getManager();
//        $dql = "SELECT e from LogicBundle:CategoriaEvento e";
//        $eventos = ResponseBuilder::getCollection($request, $em, $dql);
//        if ($eventos === null) {
//            return new View("there are no events exist", Response::HTTP_NOT_FOUND);
//        }
//        return $eventos;
//    }
//
//}
