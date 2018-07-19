<?php

namespace LogicBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PreinscripcionOfertaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PreinscripcionOfertaRepository extends EntityRepository {

    public function buscarOfertaPreinscritos($oferta) {
        $em = $this->getEntityManager();

        $query = $em->getRepository('LogicBundle:PreinscripcionOferta')
                ->createQueryBuilder("p");

        $query
                ->leftJoin('p.oferta', 'o')
                ->join('p.usuario', 'u')
                ->andWhere("o.id = :oferta")
                ->andWhere("p.activo = :activo")
                ->setParameters([
                    'oferta' => $oferta,
                    'activo' => true
                ])
                ->orderBy('u.lastname', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

}