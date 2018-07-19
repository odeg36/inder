<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanMetodologicoActividad
 *
 * @ORM\Table(name="plan_metodologico_actividad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PlanMetodologicoActividadRepository")
 */
class PlanMetodologicoActividad
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
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;
    
    /**
     * @ORM\ManyToOne(targetEntity="PlanMetodologico", inversedBy="planMetodologicoActividades", cascade={"persist"})
     * @ORM\JoinColumn(name="plan_metodologico_id", referencedColumnName="id")
     */
    private $planMetodologico;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return PlanMetodologicoActividad
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico|null $planMetodologico
     *
     * @return PlanMetodologicoActividad
     */
    public function setPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico = null)
    {
        $this->planMetodologico = $planMetodologico;

        return $this;
    }

    /**
     * Get planMetodologico.
     *
     * @return \LogicBundle\Entity\PlanMetodologico|null
     */
    public function getPlanMetodologico()
    {
        return $this->planMetodologico;
    }
}
