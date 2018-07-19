<?php

namespace LogicBundle\Repository;

/**
 * ProgramacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProgramacionRepository extends \Doctrine\ORM\EntityRepository {
    
    public function buscarPorgramacionHabilitada($oferta) {
        $em = $this->getEntityManager();

        $query = $em->getRepository('LogicBundle:Programacion')
            ->createQueryBuilder("p")
            ->innerJoin('p.oferta', 'o')
            ->andWhere("o.id = :oferta")
            ->andWhere("p.horaInicial IS NOT NULL")
            ->setParameters([
                'oferta' => $oferta
            ]);

        return $query;
    }
    
}