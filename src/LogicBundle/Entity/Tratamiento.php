<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tratamiento
 *
 * @ORM\Table(name="tratamiento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TratamientoRepository")
 */
class Tratamiento {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Medicamento", inversedBy="tratamientos")
     * @ORM\JoinColumn(name="medicamento_id", referencedColumnName="id", nullable=true)
     */
    private $medicamento;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var int
     *
     * @ORM\Column(name="frecuencia_numero", type="integer", nullable=true)
     */
    private $frecuenciaNumero;

    /**
     * @var string
     *
     * @ORM\Column(name="frecuencia_dia", type="string", length=255, nullable=true)
     */
    private $frecuenciaDia;

    /**
     * @var int
     *
     * @ORM\Column(name="duracion_numero", type="integer", nullable=true)
     */
    private $duracionNumero;

    /**
     * @var int
     *
     * @ORM\Column(name="duracion_tiempo", type="integer", nullable=true)
     */
    private $duracionTiempo;

    /**
     * @var int
     *
     * @ORM\Column(name="duracion_cantidad_total", type="integer", nullable=true)
     */
    private $duracionCantidadTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="duracion_observaciones", type="text", nullable=true)
     */
    private $duracionObservaciones;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaMedico", inversedBy="tratamientos")
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
     * @return Tratamiento
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
     * Set frecuenciaNumero
     *
     * @param integer $frecuenciaNumero
     *
     * @return Tratamiento
     */
    public function setFrecuenciaNumero($frecuenciaNumero)
    {
        $this->frecuenciaNumero = $frecuenciaNumero;

        return $this;
    }

    /**
     * Get frecuenciaNumero
     *
     * @return integer
     */
    public function getFrecuenciaNumero()
    {
        return $this->frecuenciaNumero;
    }

    /**
     * Set frecuenciaDia
     *
     * @param string $frecuenciaDia
     *
     * @return Tratamiento
     */
    public function setFrecuenciaDia($frecuenciaDia)
    {
        $this->frecuenciaDia = $frecuenciaDia;

        return $this;
    }

    /**
     * Get frecuenciaDia
     *
     * @return string
     */
    public function getFrecuenciaDia()
    {
        return $this->frecuenciaDia;
    }

    /**
     * Set duracionNumero
     *
     * @param integer $duracionNumero
     *
     * @return Tratamiento
     */
    public function setDuracionNumero($duracionNumero)
    {
        $this->duracionNumero = $duracionNumero;

        return $this;
    }

    /**
     * Get duracionNumero
     *
     * @return integer
     */
    public function getDuracionNumero()
    {
        return $this->duracionNumero;
    }

    /**
     * Set duracionTiempo
     *
     * @param integer $duracionTiempo
     *
     * @return Tratamiento
     */
    public function setDuracionTiempo($duracionTiempo)
    {
        $this->duracionTiempo = $duracionTiempo;

        return $this;
    }

    /**
     * Get duracionTiempo
     *
     * @return integer
     */
    public function getDuracionTiempo()
    {
        return $this->duracionTiempo;
    }

    /**
     * Set duracionCantidadTotal
     *
     * @param integer $duracionCantidadTotal
     *
     * @return Tratamiento
     */
    public function setDuracionCantidadTotal($duracionCantidadTotal)
    {
        $this->duracionCantidadTotal = $duracionCantidadTotal;

        return $this;
    }

    /**
     * Get duracionCantidadTotal
     *
     * @return integer
     */
    public function getDuracionCantidadTotal()
    {
        return $this->duracionCantidadTotal;
    }

    /**
     * Set duracionObservaciones
     *
     * @param string $duracionObservaciones
     *
     * @return Tratamiento
     */
    public function setDuracionObservaciones($duracionObservaciones)
    {
        $this->duracionObservaciones = $duracionObservaciones;

        return $this;
    }

    /**
     * Get duracionObservaciones
     *
     * @return string
     */
    public function getDuracionObservaciones()
    {
        return $this->duracionObservaciones;
    }

    /**
     * Set medicamento
     *
     * @param \LogicBundle\Entity\Medicamento $medicamento
     *
     * @return Tratamiento
     */
    public function setMedicamento(\LogicBundle\Entity\Medicamento $medicamento = null)
    {
        $this->medicamento = $medicamento;

        return $this;
    }

    /**
     * Get medicamento
     *
     * @return \LogicBundle\Entity\Medicamento
     */
    public function getMedicamento()
    {
        return $this->medicamento;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return Tratamiento
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
