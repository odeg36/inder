<?php

namespace ServicesBundle\Tools;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Description of ResponseBuilder
 *
 * @author mherran
 */
class ResponseBuilder {

    protected $entityManager;
    protected $request;
    protected $container;

    public function __construct(EntityManager $defaultEntityManager, RequestStack $request) {
        $this->entityManager = $defaultEntityManager;
        $this->request = $request->getCurrentRequest();
    }

    public function getCollection($dql, $parameters = array()) {
        $pagina = $this->request->get('pagina', 1);
        $elementos_por_pagina = $this->request->get('elementos_por_pagina', 10);
        $query = $this->entityManager->createQuery($dql);

        if ($elementos_por_pagina > 0) {
            $query->setFirstResult(($pagina - 1) * $elementos_por_pagina);
            $query->setMaxResults($elementos_por_pagina);
        }

        foreach ($parameters as $name => $value) {
            $query->setParameter($name, $value);
        }
        $paginator = new Paginator($query, $fetchJoinCollection = false);

        $c = count($paginator->getIterator()->getArrayCopy());
        $data = array(
            'totalItems' => $c,
            'pagina' => $pagina,
            'elementos_por_pagina' => $elementos_por_pagina,
            'items' => $paginator->getIterator()->getArrayCopy()
        );
        return $data;
    }

    public function getItem($dql, $parameters) {

        $query = $this->entityManager->createQuery($dql);

        foreach ($parameters as $name => $value) {
            $query->setParameter($name, $value);
        }
        $item = $query->getOneOrNullResult();
        return $item;
    }

}
