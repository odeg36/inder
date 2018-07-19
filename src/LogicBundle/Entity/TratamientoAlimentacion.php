<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TratamientoAlimentacion
 *
 * @ORM\Table(name="tratamiento_alimentacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TratamientoAlimentacionRepository")
 */
class TratamientoAlimentacion
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
     * @var string
     *
     * @ORM\Column(name="tratamiento", type="text")
     */
    private $tratamiento;

    /**
     * @var string
     *
     * @ORM\Column(name="plan_alimentacion", type="text")
     */
    private $planAlimentacion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="tratamientoAlimentacion")
     * @ORM\JoinColumn(name="tratamiento_alimentacion_id", referencedColumnName="id")
     */
    private $nutricion;

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
     * Set tratamiento
     *
     * @param string $tratamiento
     *
     * @return TratamientoAlimentacion
     */
    public function setTratamiento($tratamiento)
    {
        $this->tratamiento = $tratamiento;

        return $this;
    }

    /**
     * Get tratamiento
     *
     * @return string
     */
    public function getTratamiento()
    {
        return $this->tratamiento;
    }

    /**
     * Set planAlimentacion
     *
     * @param string $planAlimentacion
     *
     * @return TratamientoAlimentacion
     */
    public function setPlanAlimentacion($planAlimentacion)
    {
        $this->planAlimentacion = $planAlimentacion;

        return $this;
    }

    /**
     * Get planAlimentacion
     *
     * @return string
     */
    public function getPlanAlimentacion()
    {
        return $this->planAlimentacion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return TratamientoAlimentacion
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
}
