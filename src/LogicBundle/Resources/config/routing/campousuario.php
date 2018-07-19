<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('logicBundleCampo_index', new Route(
    '/',
    array('_controller' => 'LogicBundle:CampoUsuario:index'),
    array(),
    array(),
    '',
    array(),
    array('GET')
));

$collection->add('logicBundleCampo_show', new Route(
    '/{id}/show',
    array('_controller' => 'LogicBundle:CampoUsuario:show'),
    array(),
    array(),
    '',
    array(),
    array('GET')
));

$collection->add('logicBundleCampo_new', new Route(
    '/new',
    array('_controller' => 'LogicBundle:CampoUsuario:new'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('logicBundleCampo_edit', new Route(
    '/{id}/edit',
    array('_controller' => 'LogicBundle:CampoUsuario:edit'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('logicBundleCampo_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'LogicBundle:CampoUsuario:delete'),
    array(),
    array(),
    '',
    array(),
    array('DELETE')
));

return $collection;
