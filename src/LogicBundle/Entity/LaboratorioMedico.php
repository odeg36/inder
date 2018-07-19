<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LaboratorioMedico
 *
 * @ORM\Table(name="laboratorio_medico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\LaboratorioMedicoRepository")
 */
class LaboratorioMedico {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AyudaLaboratorio", inversedBy="laboratoriosMedicos")
     * @ORM\JoinColumn(name="ayuda_laboratorio_id", referencedColumnName="id")
     */
    private $ayudaDxParaclinico;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaMedico", inversedBy="laboratoriosMedicos")
     * @ORM\JoinColumn(name="consulta_medico_id", referencedColumnName="id")
     */
    private $consultaMedico;
   
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
     * @return LaboratorioMedico
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return LaboratorioMedico
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set ayudaDxParaclinico
     *
     * @param \LogicBundle\Entity\AyudaLaboratorio $ayudaDxParaclinico
     *
     * @return LaboratorioMedico
     */
    public function setAyudaDxParaclinico(\LogicBundle\Entity\AyudaLaboratorio $ayudaDxParaclinico = null)
    {
        $this->ayudaDxParaclinico = $ayudaDxParaclinico;

        return $this;
    }

    /**
     * Get ayudaDxParaclinico
     *
     * @return \LogicBundle\Entity\AyudaLaboratorio
     */
    public function getAyudaDxParaclinico()
    {
        return $this->ayudaDxParaclinico;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return LaboratorioMedico
     */
    public function setConsultaMedico(\LogicBundle\Entity\ConsultaMedico $consultaMedico = null)
    {
        $this->consultaMedico = $consultaMedico;

        return $this;
    }

    /**
     * Get consultaMedico
     *
     * @return \LogicBundle\Entity\ConsultaMedico
     */
    public function getConsultaMedico()
    {
        return $this->consultaMedico;
    }
}
