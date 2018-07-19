<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Laboratorio
 *
 * @ORM\Table(name="laboratorio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\LaboratorioRepository")
 */
class Laboratorio
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
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text")
     */
    private $observacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="ConsultaNutricion", inversedBy="laboratorios")
     * @ORM\JoinColumn(name="nutiricion_id", referencedColumnName="id")
     */
    private $nutricion;

    /**
     * @ORM\ManyToOne(targetEntity="AyudaLaboratorio", inversedBy="laboratorios")
     * @ORM\JoinColumn(name="ayuda_laboratorio_id", referencedColumnName="id")
     */
    private $ayudaLaboratorio;
    
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return Laboratorio
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Laboratorio
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return Laboratorio
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
     * Set ayudaLaboratorio
     *
     * @param \LogicBundle\Entity\AyudaLaboratorio $ayudaLaboratorio
     *
     * @return Laboratorio
     */
    public function setAyudaLaboratorio(\LogicBundle\Entity\AyudaLaboratorio $ayudaLaboratorio = null)
    {
        $this->ayudaLaboratorio = $ayudaLaboratorio;

        return $this;
    }

    /**
     * Get ayudaLaboratorio
     *
     * @return \LogicBundle\Entity\AyudaLaboratorio
     */
    public function getAyudaLaboratorio()
    {
        return $this->ayudaLaboratorio;
    }
}
