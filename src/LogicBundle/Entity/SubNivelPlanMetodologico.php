<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubNivelPlanMetodologico
 *
 * @ORM\Table(name="sub_nivel_plan_metodologico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubNivelPlanMetodologicoRepository")
 */
class SubNivelPlanMetodologico
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
