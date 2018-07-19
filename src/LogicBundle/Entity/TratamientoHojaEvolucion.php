<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TratamientoHojaEvolucion
 *
 * @ORM\Table(name="tratamiento_hoja_evolucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TratamientoHojaEvolucionRepository")
 */
class TratamientoHojaEvolucion {

    /**
     * Get frecuenciaDias
     *
     * @return string
     */
    public function getFrecuenciaDias() {
        $frecuenciaDias = [
            1 => 'Horas',
            2 => 'Semanas',
            3 => 'Meses',
            4 => 'Dias',
        ];
        if ($this->frecuenciaDias) {
            return $frecuenciaDias[$this->frecuenciaDias];
        } else {
            return "";
        }
    }
    
    
    /**
     * Get duracionTiempo
     *
     * @return integer
     */
    public function getDuracionTiempo() {
        $frecuenciaDias = [
            1 => 'Horas',
            2 => 'Semanas',
            3 => 'Meses',
            4 => 'Dias',
        ];
        if ($this->duracionTiempo) {
            return $frecuenciaDias[$this->duracionTiempo];
        } else {
            return "";
        }
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
     * @ORM\ManyToOne(targetEntity="Medicamento", inversedBy="tratamientosHojasEvoluciones")
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
     * @ORM\Column(name="frecuenciaNumero", type="integer", nullable=true)
     */
    private $frecuenciaNumero;

    /**
     * @var int
     *
     * @ORM\Column(name="frecuenciaDias", type="integer", nullable=true)
     */
    private $frecuenciaDias;

    /**
     * @var int
     *
     * @ORM\Column(name="duracionNumero", type="integer", nullable=true)
     */
    private $duracionNumero;

    /**
     * @var int
     *
     * @ORM\Column(name="duracionTiempo", type="integer", nullable=true)
     */
    private $duracionTiempo;

    /**
     * @var int
     *
     * @ORM\Column(name="duracionTotal", type="integer", nullable=true)
     */
    private $duracionTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;

    /**
     * @ORM\ManyToOne(targetEntity="HojaEvolucion", inversedBy="tratamientosHojasEvoluciones")
     * @ORM\JoinColumn(name="hoja_evolucion_id", referencedColumnName="id")
     */
    private $hojaEvolucion;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TratamientoHojaEvolucion
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * Set frecuenciaNumero
     *
     * @param integer $frecuenciaNumero
     *
     * @return TratamientoHojaEvolucion
     */
    public function setFrecuenciaNumero($frecuenciaNumero) {
        $this->frecuenciaNumero = $frecuenciaNumero;

        return $this;
    }

    /**
     * Get frecuenciaNumero
     *
     * @return integer
     */
    public function getFrecuenciaNumero() {
        return $this->frecuenciaNumero;
    }

    /**
     * Set frecuenciaDias
     *
     * @param integer $frecuenciaDias
     *
     * @return TratamientoHojaEvolucion
     */
    public function setFrecuenciaDias($frecuenciaDias) {
        $this->frecuenciaDias = $frecuenciaDias;

        return $this;
    }

    /**
     * Set duracionNumero
     *
     * @param integer $duracionNumero
     *
     * @return TratamientoHojaEvolucion
     */
    public function setDuracionNumero($duracionNumero) {
        $this->duracionNumero = $duracionNumero;

        return $this;
    }

    /**
     * Get duracionNumero
     *
     * @return integer
     */
    public function getDuracionNumero() {
        return $this->duracionNumero;
    }

    /**
     * Set duracionTiempo
     *
     * @param integer $duracionTiempo
     *
     * @return TratamientoHojaEvolucion
     */
    public function setDuracionTiempo($duracionTiempo) {
        $this->duracionTiempo = $duracionTiempo;

        return $this;
    }

    /**
     * Set duracionTotal
     *
     * @param integer $duracionTotal
     *
     * @return TratamientoHojaEvolucion
     */
    public function setDuracionTotal($duracionTotal) {
        $this->duracionTotal = $duracionTotal;

        return $this;
    }

    /**
     * Get duracionTotal
     *
     * @return integer
     */
    public function getDuracionTotal() {
        return $this->duracionTotal;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return TratamientoHojaEvolucion
     */
    public function setObservacion($observacion) {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion() {
        return $this->observacion;
    }

    /**
     * Set medicamento
     *
     * @param \LogicBundle\Entity\Medicamento $medicamento
     *
     * @return TratamientoHojaEvolucion
     */
    public function setMedicamento(\LogicBundle\Entity\Medicamento $medicamento = null) {
        $this->medicamento = $medicamento;

        return $this;
    }

    /**
     * Get medicamento
     *
     * @return \LogicBundle\Entity\Medicamento
     */
    public function getMedicamento() {
        return $this->medicamento;
    }

    /**
     * Set hojaEvolucion
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojaEvolucion
     *
     * @return TratamientoHojaEvolucion
     */
    public function setHojaEvolucion(\LogicBundle\Entity\HojaEvolucion $hojaEvolucion = null) {
        $this->hojaEvolucion = $hojaEvolucion;

        return $this;
    }

    /**
     * Get hojaEvolucion
     *
     * @return \LogicBundle\Entity\HojaEvolucion
     */
    public function getHojaEvolucion() {
        return $this->hojaEvolucion;
    }

}
