<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="evaluacion_posicion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EvaluacionPosicionRepository")
 */
class EvaluacionPosicion {

    const POSICION_BIPIDO = 'Bipido o sentado';
    const POSICION_BIPIDO_PRONO = 'Prono';
    const FUNCIONAL_FUNCIONAL = 'Funcional';
    const FUNCIONAL_ACEPTABLE = 'Aceptable';
    const FUNCIONAL_POCO_FUNCIONAL = 'Poco funcional';
    const FUNCIONAL_NO_FUNCIONAL = 'No funcional';

    public function __toString() {
        return (string) $this->getConsultaFisioterapia()->getDeportista() ?: '';
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
     * @ORM\Column(name="posicion", type="string", length=255, nullable=true)
     */
    private $posicion;

    /**
     * @var string
     *
     * @ORM\Column(name="funcional", type="string", length=255, nullable=true)
     */
    private $funcional;

    /**
     * @ORM\ManyToOne(targetEntity="MovimientoPosicion", inversedBy="evaluacionesPosicion")
     * @ORM\JoinColumn(name="movimiento_posicion_id", referencedColumnName="id", nullable=true)
     */
    private $movimientoPosicion; 

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaFisioterapia", inversedBy="evaluacionesPosicion")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;



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
     * Set posicion
     *
     * @param string $posicion
     *
     * @return EvaluacionPosicion
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return string
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set funcional
     *
     * @param string $funcional
     *
     * @return EvaluacionPosicion
     */
    public function setFuncional($funcional)
    {
        $this->funcional = $funcional;

        return $this;
    }

    /**
     * Get funcional
     *
     * @return string
     */
    public function getFuncional()
    {
        return $this->funcional;
    }

    /**
     * Set movimientoPosicion
     *
     * @param \LogicBundle\Entity\MovimientoPosicion $movimientoPosicion
     *
     * @return EvaluacionPosicion
     */
    public function setMovimientoPosicion(\LogicBundle\Entity\MovimientoPosicion $movimientoPosicion = null)
    {
        $this->movimientoPosicion = $movimientoPosicion;

        return $this;
    }

    /**
     * Get movimientoPosicion
     *
     * @return \LogicBundle\Entity\MovimientoPosicion
     */
    public function getMovimientoPosicion()
    {
        return $this->movimientoPosicion;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return EvaluacionPosicion
     */
    public function setConsultaFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia = null)
    {
        $this->consultaFisioterapia = $consultaFisioterapia;

        return $this;
    }

    /**
     * Get consultaFisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getConsultaFisioterapia()
    {
        return $this->consultaFisioterapia;
    }
}
