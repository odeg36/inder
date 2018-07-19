<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reserva
 *
 * @ORM\Table(name="reserva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ReservaRepository")
 */
class Reserva {

    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @var \Date
     *
     * @ORM\Column(name="fecha_inicio", type="date")
     */
    private $fechaInicio;

    /**
     * @var \string
     *
     * @ORM\Column(name="estado",  type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @var \Date
     *
     * @ORM\Column(name="fecha_final", type="date")
     */
    private $fechaFinal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reservaTodoEscenario", type="boolean", nullable=true)
     */
    private $reservaTodoEscenario;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="completada", type="boolean", nullable=true)
     */
    private $completada;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Barrio")
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id", nullable=true)
     */
    private $barrio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PuntoAtencion")
     * @ORM\JoinColumn(name="puntoAtencion_id", referencedColumnName="id", )
     */
    private $puntoAtencion;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id", nullable=true)
     */
    private $disciplina;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TendenciaEscenarioDeportivo")
     * @ORM\JoinColumn(name="tendenciaEscenarioDeportivo_id", referencedColumnName="id", nullable=true)
     */
    private $tendenciaEscenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TipoReserva")
     * @ORM\JoinColumn(name="tipo_reserva_id", referencedColumnName="id", )
     */
    private $tipoReserva;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", )
     */
    private $usuario;

    //muchos a muchos por usuario

    /**
     * Many Users have Many Reserva.
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="reservas", cascade={"persist"})
     * @ORM\JoinTable(name="reserva_usuario",
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="reserva_id", referencedColumnName="id")}
     *      )
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DiaReserva", mappedBy="reserva", cascade={"persist", "remove"});
     */
    private $diaReserva;

    /**
     * @ORM\OneToMany(targetEntity="AsistenciaReserva", mappedBy="reserva")
     */
    private $asistenciaReservas;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DivisionReserva", mappedBy="reserva")
     */
    private $divisiones;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\MotivoCancelacion", inversedBy="reservas")
     * @ORM\JoinColumn(name="motivoCancelacion_id",referencedColumnName="id")
     */
    private $motivoCancelacion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ProgramacionReserva", mappedBy="reserva", cascade={"persist"});
     */
    private $programaciones;
    private $usuariosString;

    /**
     * Constructor
     */
    public function __construct() {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diaReserva = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asistenciaReservas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Reserva
     */
    public function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Reserva
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     *
     * @return Reserva
     */
    public function setFechaFinal($fechaFinal) {
        $this->fechaFinal = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime
     */
    public function getFechaFinal() {
        return $this->fechaFinal;
    }

    /**
     * Set barrio
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return Reserva
     */
    public function setBarrio(\LogicBundle\Entity\Barrio $barrio = null) {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return \LogicBundle\Entity\Barrio
     */
    public function getBarrio() {
        return $this->barrio;
    }

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return Reserva
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }

    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return Reserva
     */
    public function setDisciplina(\LogicBundle\Entity\Disciplina $disciplina = null) {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina
     *
     * @return \LogicBundle\Entity\Disciplina
     */
    public function getDisciplina() {
        return $this->disciplina;
    }

    /**
     * Set tendenciaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo
     *
     * @return Reserva
     */
    public function setTendenciaEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo = null) {
        $this->tendenciaEscenarioDeportivo = $tendenciaEscenarioDeportivo;

        return $this;
    }

    /**
     * Get tendenciaEscenarioDeportivo
     *
     * @return \LogicBundle\Entity\TendenciaEscenarioDeportivo
     */
    public function getTendenciaEscenarioDeportivo() {
        return $this->tendenciaEscenarioDeportivo;
    }

    /**
     * Set tipoReserva
     *
     * @param \LogicBundle\Entity\TipoReserva $tipoReserva
     *
     * @return Reserva
     */
    public function setTipoReserva(\LogicBundle\Entity\TipoReserva $tipoReserva = null) {
        $this->tipoReserva = $tipoReserva;

        return $this;
    }

    /**
     * Get tipoReserva
     *
     * @return \LogicBundle\Entity\TipoReserva
     */
    public function getTipoReserva() {
        return $this->tipoReserva;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Reserva
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Add usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Reserva
     */
    public function addUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     */
    public function removeUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios() {
        return $this->usuarios;
    }

    public function existeUsuario(\Application\Sonata\UserBundle\Entity\User $usuario) {
        return $this->getUsuarios()->contains($usuario);
    }

    /**
     * Add diaReserva
     *
     * @param \LogicBundle\Entity\DiaReserva $diaReserva
     *
     * @return Reserva
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
     * Add asistenciaReserva
     *
     * @param \LogicBundle\Entity\AsistenciaReserva $asistenciaReserva
     *
     * @return Reserva
     */
    public function addAsistenciaReserva(\LogicBundle\Entity\AsistenciaReserva $asistenciaReserva) {
        $this->asistenciaReservas[] = $asistenciaReserva;

        return $this;
    }

    /**
     * Remove asistenciaReserva
     *
     * @param \LogicBundle\Entity\AsistenciaReserva $asistenciaReserva
     */
    public function removeAsistenciaReserva(\LogicBundle\Entity\AsistenciaReserva $asistenciaReserva) {
        $this->asistenciaReservas->removeElement($asistenciaReserva);
    }

    /**
     * Get asistenciaReservas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsistenciaReservas() {
        return $this->asistenciaReservas;
    }

    /**
     * Get usuariosString
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuariosString() {
        $this->usuariosString = "<ul>";
        foreach ($this->getUsuarios() as $usuario) {
            if ($usuario != null) {
                $this->usuariosString = "<li>" . $usuario->getFirstname() . " " . $usuario->getLastname() . "<li>";
            }
        }
        $this->usuariosString .= "</ul>";

        return $this->usuariosString;
    }

    /**
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return Reserva
     */
    public function setPuntoAtencion(\LogicBundle\Entity\PuntoAtencion $puntoAtencion = null) {
        $this->puntoAtencion = $puntoAtencion;

        return $this;
    }

    /**
     * Get puntoAtencion
     *
     * @return \LogicBundle\Entity\PuntoAtencion
     */
    public function getPuntoAtencion() {
        return $this->puntoAtencion;
    }

    /**
     * Set motivoCancelacion
     *
     * @param \LogicBundle\Entity\MotivoCancelacion $motivoCancelacion
     *
     * @return Reserva
     */
    public function setMotivoCancelacion($motivoCancelacion) {
        $this->motivoCancelacion = $motivoCancelacion;

        return $this;
    }

    /**
     * Get motivoCancelacion
     *
     * @return \LogicBundle\Entity\MotivoCancelacion
     */
    public function getMotivoCancelacion() {
        return $this->motivoCancelacion;
    }

    /**
     * Set reservaTodoEscenario
     *
     * @param boolean $reservaTodoEscenario
     *
     * @return Reserva
     */
    public function setReservaTodoEscenario($reservaTodoEscenario) {
        $this->reservaTodoEscenario = $reservaTodoEscenario;

        return $this;
    }

    /**
     * Get reservaTodoEscenario
     *
     * @return boolean
     */
    public function getReservaTodoEscenario() {
        return $this->reservaTodoEscenario;
    }

    /**
     * Add divisione.
     *
     * @param \LogicBundle\Entity\DivisionReserva $divisione
     *
     * @return Reserva
     */
    public function addDivisione(\LogicBundle\Entity\DivisionReserva $divisione) {
        $this->divisiones[] = $divisione;

        return $this;
    }

    /**
     * Remove divisione.
     *
     * @param \LogicBundle\Entity\DivisionReserva $divisione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDivisione(\LogicBundle\Entity\DivisionReserva $divisione) {
        return $this->divisiones->removeElement($divisione);
    }

    /**
     * Get divisiones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisiones() {
        return $this->divisiones;
    }

    /**
     * Add programacione.
     *
     * @param \LogicBundle\Entity\ProgramacionReserva $programacione
     *
     * @return Reserva
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


    /**
     * Set completada.
     *
     * @param bool|null $completada
     *
     * @return Reserva
     */
    public function setCompletada($completada = null)
    {
        $this->completada = $completada;

        return $this;
    }

    /**
     * Get completada.
     *
     * @return bool|null
     */
    public function getCompletada()
    {
        return $this->completada;
    }
}
