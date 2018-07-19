<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dia
 *
 * @ORM\Table(name="dia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiaRepository")
 */
class Dia {

    public function __toString() {
        return $this->getNombre() ?: '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Programacion", mappedBy="dia")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $programacion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DiaReserva", mappedBy="dia")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $diaReserva;

    /**
     * @ORM\OneToMany(targetEntity="ActividadHorario", mappedBy="dia")
     */
    private $actividadHorarios;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ProgramacionReserva", mappedBy="dia");
     */
    private $programaciones;

    /**
     * Constructor
     */
    public function __construct() {
        $this->programacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diaReserva = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actividadHorarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Dia
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Dia
     */
    public function setNumero($numero) {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Add programacion
     *
     * @param \LogicBundle\Entity\Programacion $programacion
     *
     * @return Dia
     */
    public function addProgramacion(\LogicBundle\Entity\Programacion $programacion) {
        $this->programacion[] = $programacion;

        return $this;
    }

    /**
     * Remove programacion
     *
     * @param \LogicBundle\Entity\Programacion $programacion
     */
    public function removeProgramacion(\LogicBundle\Entity\Programacion $programacion) {
        $this->programacion->removeElement($programacion);
    }

    /**
     * Get programacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacion() {
        return $this->programacion;
    }

    /**
     * Add diaReserva
     *
     * @param \LogicBundle\Entity\DiaReserva $diaReserva
     *
     * @return Dia
     */
    public function addDiaReserva(\LogicBundle\Entity\DiaReserva $diaReserva) {
        $this->diaReserva[] = $diaReserva;

        return $this;
    }

    /**
     * Remove diaReserva
     *
     * @param \LogicBundle\Entity\DiaReserva $diaReserva
     */
    public function removeDiaReserva(\LogicBundle\Entity\DiaReserva $diaReserva) {
        $this->diaReserva->removeElement($diaReserva);
    }

    /**
     * Get diaReserva
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiaReserva() {
        return $this->diaReserva;
    }

    /**
     * Add actividadHorario
     *
     * @param \LogicBundle\Entity\ActividadHorario $actividadHorario
     *
     * @return Dia
     */
    public function addActividadHorario(\LogicBundle\Entity\ActividadHorario $actividadHorario) {
        $this->actividadHorarios[] = $actividadHorario;

        return $this;
    }

    /**
     * Remove actividadHorario
     *
     * @param \LogicBundle\Entity\ActividadHorario $actividadHorario
     */
    public function removeActividadHorario(\LogicBundle\Entity\ActividadHorario $actividadHorario) {
        $this->actividadHorarios->removeElement($actividadHorario);
    }

    /**
     * Get actividadHorarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividadHorarios() {
        return $this->actividadHorarios;
    }

    /**
     * Add programacione.
     *
     * @param \LogicBundle\Entity\ProgramacionReserva $programacione
     *
     * @return Dia
     */
    public function addProgramacione(\LogicBundle\Entity\ProgramacionReserva $programacione) {
        $this->programaciones[] = $programacione;

        return $this;
    }

    /**
     * Remove programacione.
     *
     * @param \LogicBundle\Entity\ProgramacionReserva $programacione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProgramacione(\LogicBundle\Entity\ProgramacionReserva $programacione) {
        return $this->programaciones->removeElement($programacione);
    }

    /**
     * Get programaciones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramaciones() {
        return $this->programaciones;
    }

}
