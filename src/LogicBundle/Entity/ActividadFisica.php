<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadFisica
 *
 * @ORM\Table(name="actividad_fisica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ActividadFisicaRepository")
 */
class ActividadFisica
{
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="actividadFisica")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\OneToMany(targetEntity="ActividadHorario", mappedBy="actividadFisica", cascade={"persist"})
     */
    private $actividadHorarios;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actividadHorarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return ActividadFisica
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }

    /**
     * Add actividadHorario
     *
     * @param \LogicBundle\Entity\ActividadHorario $actividadHorario
     *
     * @return ActividadFisica
     */
    public function addActividadHorario(\LogicBundle\Entity\ActividadHorario $actividadHorario)
    {
        $this->actividadHorarios[] = $actividadHorario;

        return $this;
    }

    /**
     * Remove actividadHorario
     *
     * @param \LogicBundle\Entity\ActividadHorario $actividadHorario
     */
    public function removeActividadHorario(\LogicBundle\Entity\ActividadHorario $actividadHorario)
    {
        $this->actividadHorarios->removeElement($actividadHorario);
    }

    /**
     * Get actividadHorarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividadHorarios()
    {
        return $this->actividadHorarios;
    }
}
