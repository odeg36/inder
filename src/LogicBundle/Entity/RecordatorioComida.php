<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecordatorioComida
 *
 * @ORM\Table(name="recordatorio_comida")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RecordatorioComidaRepository")
 */
class RecordatorioComida
{
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
     * @ORM\Column(name="alimentacion_preparacion", type="string", length=255, nullable=true)
     */
    private $alimentacionPreparacion;

    /**
     * @var int
     *
     * @ORM\Column(name="porcion", type="integer")
     */
    private $porcion;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="string", length=255, nullable=true)
     */
    private $observacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Recordatorio", inversedBy="recordatorioComidas")
     * @ORM\JoinColumn(name="recordatorio_id", referencedColumnName="id")
     */
    private $recordatorio;
    
    /**
     * @ORM\ManyToOne(targetEntity="Comida", inversedBy="recordatorioComidas")
     * @ORM\JoinColumn(name="comida_id", referencedColumnName="id")
     */
    private $comida;
    

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
     * Set alimentacionPreparacion
     *
     * @param string $alimentacionPreparacion
     *
     * @return RecordatorioComida
     */
    public function setAlimentacionPreparacion($alimentacionPreparacion)
    {
        $this->alimentacionPreparacion = $alimentacionPreparacion;

        return $this;
    }

    /**
     * Get alimentacionPreparacion
     *
     * @return string
     */
    public function getAlimentacionPreparacion()
    {
        return $this->alimentacionPreparacion;
    }

    /**
     * Set porcion
     *
     * @param integer $porcion
     *
     * @return RecordatorioComida
     */
    public function setPorcion($porcion)
    {
        $this->porcion = $porcion;

        return $this;
    }

    /**
     * Get porcion
     *
     * @return integer
     */
    public function getPorcion()
    {
        return $this->porcion;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return RecordatorioComida
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
     * Set recordatorio
     *
     * @param \LogicBundle\Entity\Recordatorio $recordatorio
     *
     * @return RecordatorioComida
     */
    public function setRecordatorio(\LogicBundle\Entity\Recordatorio $recordatorio = null)
    {
        $this->recordatorio = $recordatorio;

        return $this;
    }

    /**
     * Get recordatorio
     *
     * @return \LogicBundle\Entity\Recordatorio
     */
    public function getRecordatorio()
    {
        return $this->recordatorio;
    }

    /**
     * Set comida
     *
     * @param \LogicBundle\Entity\Comida $comida
     *
     * @return RecordatorioComida
     */
    public function setComida(\LogicBundle\Entity\Comida $comida = null)
    {
        $this->comida = $comida;

        return $this;
    }

    /**
     * Get comida
     *
     * @return \LogicBundle\Entity\Comida
     */
    public function getComida()
    {
        return $this->comida;
    }
}
