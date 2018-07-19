<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoReservaEscenarioDeportivoDivision
 *
 * @ORM\Table(name="tipo_reserva_escenario_deportivo_division")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoReservaEscenarioDeportivoDivisionRepository")
 */
class TipoReservaEscenarioDeportivoDivision
{
    public function __toString() {
        //return $this->tiempoMinimo ? $this->tiempoMinimo : '';
        return 'hola';
        
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivo", inversedBy="tipoReservaEscenarioDeportivoDivisiones")
     * @ORM\JoinColumn(name="tipo_reserva_escenario_deportivo_id", referencedColumnName="id", )
     */
    private $tipoReservaEscenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="Division", inversedBy="tipoReservaEscenarioDeportivoDivisiones")
     * @ORM\JoinColumn(name="division_id", referencedColumnName="id", )
     */
    private $divisionTipoReserva;


    /**
     * @var string
     *
     * @ORM\Column(name="tiempo_minimo", type="string", length=255, nullable=true)
     */
    private $tiempoMinimo;

    /**
     * @var string
     *
     * @ORM\Column(name="tiempo_maximo", type="string", length=255, nullable=true)
     */
    private $tiempoMaximo;

    /**
     * @var int
     *
     * @ORM\Column(name="bloque_tiempo", type="integer", nullable=true)
     */
    private $bloqueTiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="usuarios_minimos", type="string", length=255, nullable=true)
     */
    private $usuariosMinimos;

    /**
     * @var int
     *
     * @ORM\Column(name="usuarios_maximos", type="integer", nullable=true)
     */
    private $usuariosMaximos;
    
     /**
     * @var int
     *
     * @ORM\Column(name="dias_previos_reserva", type="integer", nullable=true)
     */
    private $diasPreviosReserva;


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
     * Set tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     *
     * @return TipoReservaEscenarioDeportivoDivision
     */
    public function setTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo = null) {
        $this->tipoReservaEscenarioDeportivo = $tipoReservaEscenarioDeportivo;
        return $this;
    }

    /**
     * Get tipoReservaEscenarioDeportivo
     *
     * @return \LogicBundle\Entity\TipoReservaEscenarioDeportivo
     */
    public function getTipoReservaEscenarioDeportivo() {
        return $this->tipoReservaEscenarioDeportivo;
    }




    /**
     * Set divisionTipoReserva
     *
     * @param \LogicBundle\Entity\Division $divisionTipoReserva
     *
     * @return TipoReservaEscenarioDeportivoDivision
     */
    public function setDivision(\LogicBundle\Entity\Division $divisionTipoReserva = null) {
        $this->divisionTipoReserva = $divisionTipoReserva;
        return $this;
    }

    /**
     * Get divisionTipoReserva
     *
     * @return \LogicBundle\Entity\Division
     */
    public function getDivision() {
        return $this->divisionTipoReserva;
    }





    /**
     * Set tiempoMinimo
     *
     * @param string $tiempoMinimo
     *
     * @return Division
     */
    public function setTiempoMinimo($tiempoMinimo) {
        $this->tiempoMinimo = $tiempoMinimo;

        return $this;
    }

    /**
     * Get tiempoMinimo
     *
     * @return string
     */
    public function getTiempoMinimo() {
        return $this->tiempoMinimo;
    }

    /**
     * Set tiempoMaximo
     *
     * @param string $tiempoMaximo
     *
     * @return Division
     */
    public function setTiempoMaximo($tiempoMaximo) {
        $this->tiempoMaximo = $tiempoMaximo;

        return $this;
    }

    /**
     * Get tiempoMaximo
     *
     * @return string
     */
    public function getTiempoMaximo() {
        return $this->tiempoMaximo;
    }

    /**
     * Set usuariosMinimos
     *
     * @param string $usuariosMinimos
     *
     * @return Division
     */
    public function setUsuariosMinimos($usuariosMinimos) {
        $this->usuariosMinimos = $usuariosMinimos;

        return $this;
    }

    /**
     * Get usuariosMinimos
     *
     * @return string
     */
    public function getUsuariosMinimos() {
        return $this->usuariosMinimos;
    }

    /**
     * Set usuariosMaximos
     *
     * @param integer $usuariosMaximos
     *
     * @return Division
     */
    public function setUsuariosMaximos($usuariosMaximos) {
        $this->usuariosMaximos = $usuariosMaximos;

        return $this;
    }

    /**
     * Get usuariosMaximos
     *
     * @return int
     */
    public function getUsuariosMaximos() {
        return $this->usuariosMaximos;
    }

    /**
     * Set bloqueTiempo
     *
     * @param integer $bloqueTiempo
     *
     * @return Division
     */
    public function setBloqueTiempo($bloqueTiempo) {
        $this->bloqueTiempo = $bloqueTiempo;

        return $this;
    }

    /**
     * Get bloqueTiempo
     *
     * @return int
     */
    public function getBloqueTiempo() {
        return $this->bloqueTiempo;
    }



    /**
     * Set divisionTipoReserva.
     *
     * @param \LogicBundle\Entity\Division|null $divisionTipoReserva
     *
     * @return TipoReservaEscenarioDeportivoDivision
     */
    public function setDivisionTipoReserva(\LogicBundle\Entity\Division $divisionTipoReserva = null)
    {
        $this->divisionTipoReserva = $divisionTipoReserva;

        return $this;
    }

    /**
     * Get divisionTipoReserva.
     *
     * @return \LogicBundle\Entity\Division|null
     */
    public function getDivisionTipoReserva()
    {
        return $this->divisionTipoReserva;
    }

    /**
     * Set diasPreviosReserva.
     *
     * @param int $diasPreviosReserva
     *
     * @return TipoReservaEscenarioDeportivoDivision
     */
    public function setDiasPreviosReserva($diasPreviosReserva)
    {
        $this->diasPreviosReserva = $diasPreviosReserva;

        return $this;
    }

    /**
     * Get diasPreviosReserva.
     *
     * @return int
     */
    public function getDiasPreviosReserva()
    {
        return $this->diasPreviosReserva;
    }
}
