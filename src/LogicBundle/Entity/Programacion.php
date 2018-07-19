<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Programacion
 *
 * @ORM\Table(name="programacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ProgramacionRepository")
 */
class Programacion {

    public function __toString() {
        return (string) $this->dia ? (string) $this->dia : '';
    }

    public function getDiaHoras() {
        if ($this->getHoraInicial()) {
            $inicio = $this->getHoraInicial() instanceof \DateTime ? $this->getHoraInicial()->format("H:i:s") :  $this->getHoraInicial();
            $fin = $this->getHoraFinal() instanceof \DateTime ? $this->getHoraFinal()->format("H:i:s") :  $this->getHoraFinal();
            return $this->dia->getNombre() . " " . $inicio . " - " . $fin;
        }else{
            return $this->dia->getNombre();
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Dia", inversedBy="programacion");
     */
    private $dia;

    /**
     * @var time
     *
     * @ORM\Column(name="hora_inicial", type="time", nullable=true)
     */
    private $horaInicial;

    /**
     * @var time
     *
     * @ORM\Column(name="hora_final", type="time", nullable=true)
     */
    private $horaFinal;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Oferta", inversedBy="programacion")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $oferta;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
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
     * @ORM\OneToMany(targetEntity="Asistencia", mappedBy="programacion")
     */
    private $asistencias;

    /**
     * Constructor
     */
    public function __construct() {
        $this->asistencias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set horaInicial
     *
     * @param string $horaInicial
     *
     * @return Programacion
     */
    public function setHoraInicial($horaInicial) {
        $this->horaInicial = $horaInicial;

        return $this;
    }

    /**
     * Get horaInicial
     *
     * @return string
     */
    public function getHoraInicial() {
        return $this->horaInicial;
    }

    /**
     * Set horaFinal
     *
     * @param string $horaFinal
     *
     * @return Programacion
     */
    public function setHoraFinal($horaFinal) {
        $this->horaFinal = $horaFinal;

        return $this;
    }

    /**
     * Get horaFinal
     *
     * @return string
     */
    public function getHoraFinal() {
        return $this->horaFinal;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Programacion
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Programacion
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Set dia
     *
     * @param \LogicBundle\Entity\Dia $dia
     *
     * @return Programacion
     */
    public function setDia(\LogicBundle\Entity\Dia $dia = null) {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \LogicBundle\Entity\Dia
     */
    public function getDia() {
        return $this->dia;
    }

    /**
     * Set oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return Programacion
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null) {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \LogicBundle\Entity\Oferta
     */
    public function getOferta() {
        return $this->oferta;
    }

    /**
     * Add asistencia
     *
     * @param \LogicBundle\Entity\Asistencia $asistencia
     *
     * @return Programacion
     */
    public function addAsistencia(\LogicBundle\Entity\Asistencia $asistencia) {
        $this->asistencias[] = $asistencia;

        return $this;
    }

    /**
     * Remove asistencia
     *
     * @param \LogicBundle\Entity\Asistencia $asistencia
     */
    public function removeAsistencia(\LogicBundle\Entity\Asistencia $asistencia) {
        $this->asistencias->removeElement($asistencia);
    }

    /**
     * Get asistencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsistencias() {
        return $this->asistencias;
    }

}
