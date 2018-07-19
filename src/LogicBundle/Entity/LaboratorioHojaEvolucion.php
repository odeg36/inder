<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LaboratorioHojaEvolucion
 *
 * @ORM\Table(name="laboratorio_hoja_evolucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\LaboratorioHojaEvolucionRepository")
 */
class LaboratorioHojaEvolucion {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AyudaLaboratorio", inversedBy="laboratoriosHojasEvoluciones")
     * @ORM\JoinColumn(name="ayuda_laboratorio_id", referencedColumnName="id", nullable=true)
     */
    private $ayudaParaclinico;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true, nullable=true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity="HojaEvolucion", inversedBy="laboratoriosHojasEvoluciones")
     * @ORM\JoinColumn(name="hoja_evolucion_id", referencedColumnName="id")
     */
    private $hojaEvolucion;

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
     * @return LaboratorioHojaEvolucion
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
     * @return LaboratorioHojaEvolucion
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
     * Set ayudaParaclinico
     *
     * @param \LogicBundle\Entity\AyudaLaboratorio $ayudaParaclinico
     *
     * @return LaboratorioHojaEvolucion
     */
    public function setAyudaParaclinico(\LogicBundle\Entity\AyudaLaboratorio $ayudaParaclinico = null)
    {
        $this->ayudaParaclinico = $ayudaParaclinico;

        return $this;
    }

    /**
     * Get ayudaParaclinico
     *
     * @return \LogicBundle\Entity\AyudaLaboratorio
     */
    public function getAyudaParaclinico()
    {
        return $this->ayudaParaclinico;
    }

    /**
     * Set hojaEvolucion
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojaEvolucion
     *
     * @return LaboratorioHojaEvolucion
     */
    public function setHojaEvolucion(\LogicBundle\Entity\HojaEvolucion $hojaEvolucion = null)
    {
        $this->hojaEvolucion = $hojaEvolucion;

        return $this;
    }

    /**
     * Get hojaEvolucion
     *
     * @return \LogicBundle\Entity\HojaEvolucion
     */
    public function getHojaEvolucion()
    {
        return $this->hojaEvolucion;
    }
}
