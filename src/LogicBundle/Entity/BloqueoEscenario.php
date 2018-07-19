<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BloqueoEscenario
 *
 * @ORM\Table(name="bloqueo_escenario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\BloqueoEscenarioRepository")
 */
class BloqueoEscenario {

    public function __toString() {
        return $this->getEscenarioDeportivo() ? $this->getEscenarioDeportivo()->getNombre() : "";
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicial", type="date")
     */
    private $fechaInicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFinal", type="date")
     */
    private $fechaFinal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaInicial", type="time")
     */
    private $horaInicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaFinal", type="time")
     */
    private $horaFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoReserva")
     * @ORM\JoinColumn(name="tipo_reserva_id", referencedColumnName="id", )
     */
    private $tipoReserva;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create" )
     * @ORM\Column(name="fecha_creacion", type="date" )
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DivisionBloqueo", mappedBy="bloqueos", cascade={"persist"})
     */
    private $divisionesBLoqueo;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fechaInicial.
     *
     * @param \DateTime $fechaInicial
     *
     * @return BloqueoEscenario
     */
    public function setFechaInicial($fechaInicial) {
        $this->fechaInicial = $fechaInicial;

        return $this;
    }

    /**
     * Get fechaInicial.
     *
     * @return \DateTime
     */
    public function getFechaInicial() {
        return $this->fechaInicial;
    }

    /**
     * Set fechaFinal.
     *
     * @param \DateTime $fechaFinal
     *
     * @return BloqueoEscenario
     */
    public function setFechaFinal($fechaFinal) {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal.
     *
     * @return \DateTime
     */
    public function getFechaFinal() {
        return $this->fechaFinal;
    }

    /**
     * Set horaInicial.
     *
     * @param \DateTime $horaInicial
     *
     * @return BloqueoEscenario
     */
    public function setHoraInicial($horaInicial) {
        $this->horaInicial = $horaInicial;

        return $this;
    }

    /**
     * Get horaInicial.
     *
     * @return \DateTime
     */
    public function getHoraInicial() {
        return $this->horaInicial;
    }

    /**
     * Set horaFinal.
     *
     * @param \DateTime $horaFinal
     *
     * @return BloqueoEscenario
     */
    public function setHoraFinal($horaFinal) {
        $this->horaFinal = $horaFinal;

        return $this;
    }

    /**
     * Get horaFinal.
     *
     * @return \DateTime
     */
    public function getHoraFinal() {
        return $this->horaFinal;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return BloqueoEscenario
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->divisionesBLoqueo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set escenarioDeportivo.
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo|null $escenarioDeportivo
     *
     * @return BloqueoEscenario
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }

    /**
     * Get escenarioDeportivo.
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo|null
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
     * Set tipoReserva.
     *
     * @param \LogicBundle\Entity\TipoReserva|null $tipoReserva
     *
     * @return BloqueoEscenario
     */
    public function setTipoReserva(\LogicBundle\Entity\TipoReserva $tipoReserva = null) {
        $this->tipoReserva = $tipoReserva;

        return $this;
    }

    /**
     * Get tipoReserva.
     *
     * @return \LogicBundle\Entity\TipoReserva|null
     */
    public function getTipoReserva() {
        return $this->tipoReserva;
    }

    /**
     * Add divisionesBLoqueo.
     *
     * @param \LogicBundle\Entity\DivisionBloqueo $divisionesBLoqueo
     *
     * @return BloqueoEscenario
     */
    public function addDivisionesBLoqueo(\LogicBundle\Entity\DivisionBloqueo $divisionesBLoqueo) {
        $this->divisionesBLoqueo[] = $divisionesBLoqueo;

        return $this;
    }

    /**
     * Remove divisionesBLoqueo.
     *
     * @param \LogicBundle\Entity\DivisionBloqueo $divisionesBLoqueo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDivisionesBLoqueo(\LogicBundle\Entity\DivisionBloqueo $divisionesBLoqueo) {
        return $this->divisionesBLoqueo->removeElement($divisionesBLoqueo);
    }

    /**
     * Get divisionesBLoqueo.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisionesBLoqueo() {
        return $this->divisionesBLoqueo;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return BloqueoEscenario
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return BloqueoEscenario
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

}
