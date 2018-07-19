<?php

namespace LogicBundle\Repository;

/**
 * UsuarioEscenarioDeportivoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioEscenarioDeportivoRepository extends \Doctrine\ORM\EntityRepository {

    public function filtroUsuarioPorRol($query, $rol, $value) {
        $query
                ->innerJoin($query->getRootAlias() . '.groups', 'g')
                ->where("g.roles LIKE '%" . $rol . "%'")
                ->andWhere($query->getRootAlias() . ".numeroIdentificacion like '%" . $value . "%'");
        return $query;
    }

}
