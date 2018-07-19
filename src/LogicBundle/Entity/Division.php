<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Division
 *
 * @ORM\Table(name="division")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DivisionRepository")
 */
class Division {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @ORM\Column(name="edad_minima", type="integer")
     */
    private $edadMinima;

    /**
     * @var boolean
     *
     * @ORM\Column(name="necesita_aprobacion", type="boolean", nullable=true)
     */
    private $necesitaAprobacion;

    /**
     * @var boolean
     * validar disponibilidad de un escenario 
     */
    private $disponibilidad;

    /**
     * @var string
     * validar disponibilidad de un escenario 
     */
    private $errorDisponibilidad;

    /**
     * @var int
     * validar disponibilidad de un escenario 
     */
    private $numeroErrorDisponibilidad;

    /**
     * @var boolean
     * validar disponibilidadDias de un escenario 
     */
    private $disponibilidadDias;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="divisiones", cascade={"persist"})
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="division");
     */
    private $eventos;


    /**
     * Many Division have Many TiposReservaEscenarioDeportivo.
     * @ORM\ManyToMany(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivo")
     * @ORM\JoinTable(name="division_tipoReservaEscenarioDeportivo",
     *      joinColumns={@ORM\JoinColumn(name="division_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tipo_reserva_escenario_deportivo_id", referencedColumnName="id")}
     *      )
     */
    private $tiposReservaEscenarioDeportivo;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaDivision")
     * @ORM\JoinColumn(name="categoria_division_id", referencedColumnName="id", )
     */
    private $categoriaDivision;

    /**
     * Many Division have Many DisciplinasEscenarioDeportivo.
     * @ORM\ManyToMany(targetEntity="DisciplinasEscenarioDeportivo")
     * @ORM\JoinTable(name="disciplina_division",
     *      joinColumns={@ORM\JoinColumn(name="division_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")}
     *      )
     */
    private $disciplinasEscenarioDeportivo;

    /**
     * Many Division have Many TendenciaEscenarioDeportivo.
     * @ORM\ManyToMany(targetEntity="TendenciaEscenarioDeportivo")
     * @ORM\JoinTable(name="tendencia_division",
     *      joinColumns={@ORM\JoinColumn(name="division_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tendencia_id", referencedColumnName="id")}
     *      )
     */
    private $tendenciasEscenarioDeportivo;

    /* horarios de la mañana */

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_lunes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_lunes", type="string", length=255, nullable=true)
     */
    private $hora_final_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_martes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_martes", type="string", length=255, nullable=true)
     */
    private $hora_final_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_inicial_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_final_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_jueves", type="string", length=255, nullable=true)
     */
    private $hora_inicial_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_jueves", type="string", length=255, nullable=true)
     */
    private $hora_final_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_viernes", type="string", length=255, nullable=true)
     */
    private $hora_inicial_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_viernes", type="string", length=255, nullable=true)
     */
    private $hora_final_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_sabado", type="string", length=255, nullable=true)
     */
    private $hora_inicial_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_sabado", type="string", length=255, nullable=true)
     */
    private $hora_final_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial_domingo", type="string", length=255, nullable=true)
     */
    private $hora_inicial_domingo;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final_domingo", type="string", length=255, nullable=true)
     */
    private $hora_final_domingo;



    /* horarios para la  tarde */

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_lunes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_lunes", type="string", length=255, nullable=true)
     */
    private $hora_final2_lunes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_martes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_martes", type="string", length=255, nullable=true)
     */
    private $hora_final2_martes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_miercoles", type="string", length=255, nullable=true)
     */
    private $hora_final2_miercoles;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_jueves", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_jueves", type="string", length=255, nullable=true)
     */
    private $hora_final2_jueves;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_viernes", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_viernes", type="string", length=255, nullable=true)
     */
    private $hora_final2_viernes;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_sabado", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_sabado", type="string", length=255, nullable=true)
     */
    private $hora_final2_sabado;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_inicial2_domingo", type="string", length=255, nullable=true)
     */
    private $hora_inicial2_domingo;

    /**
     * @var string
     *
     * @ORM\Column(name="hora_final2_domingo", type="string", length=255, nullable=true)
     */
    private $hora_final2_domingo;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision", mappedBy="divisionTipoReserva", orphanRemoval=true)
     */
    private $tipoReservaEscenarioDeportivoDivisiones;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\OfertaDivision", mappedBy="division")
     */
    private $divisiones;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DivisionReserva", mappedBy="division")
     */
    private $reservas;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DivisionBloqueo", mappedBy="division")
     */
    private $bloqueos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoReservaEscenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoriaDivision = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplinasEscenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendenciasEscenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tipoReservaEscenarioDeportivoDivisiones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tipoReservaEscenarioDeportivoDivision
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision
     *
     * @return Division
     */
    public function addTipoReservaEscenarioDeportivoDivision(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision) {
        $this->tipoReservaEscenarioDeportivoDivisiones[] = $tipoReservaEscenarioDeportivoDivision;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivoDivision
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision
     */
    public function removeTipoReservaEscenarioDeportivoDivision(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivision) {
        $this->tipoReservaEscenarioDeportivoDivisiones->removeElement($tipoReservaEscenarioDeportivoDivision);
    }

    /**
     * Get tipoReservaEscenarioDeportivoDivisiones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoReservaEscenarioDeportivoDivisiones() {
        return $this->tipoReservaEscenarioDeportivoDivisiones;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Division
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
     * Set disponibilidad
     *
     * @param boolean $disponibilidad
     *
     * @return Division
     */
    public function setDisponibilidad($disponibilidad) {
        $this->disponibilidad = $disponibilidad;

        return $this;
    }

    /**
     * Get disponibilidad
     *
     * @return boolean
     */
    public function getDisponibilidad() {
        return $this->disponibilidad;
    }

    /**
     * Set errorDisponibilidad
     *
     * @param string $errorDisponibilidad
     *
     * @return Division
     */
    public function setErrorDisponibilidad($errorDisponibilidad) {
        $this->errorDisponibilidad = $errorDisponibilidad;

        return $this;
    }

    /**
     * Get errorDisponibilidad
     *
     * @return string
     */
    public function getErrorDisponibilidad() {
        return $this->errorDisponibilidad;
    }

    /**
     * Set numeroErrorDisponibilidad
     *
     * @param string $numeroErrorDisponibilidad
     *
     * @return Division
     */
    public function setNumeroErrorDisponibilidad($numeroErrorDisponibilidad) {
        $this->numeroErrorDisponibilidad = $numeroErrorDisponibilidad;

        return $this;
    }

    /**
     * Get numeroErrorDisponibilidad
     *
     * @return string
     */
    public function getNumeroErrorDisponibilidad() {
        return $this->numeroErrorDisponibilidad;
    }

    /**
     * Set edadMinima
     *
     * @param integer $edadMinima
     *
     * @return Division
     */
    public function setEdadMinima($edadMinima) {
        $this->edadMinima = $edadMinima;

        return $this;
    }

    /**
     * Get edadMinima
     *
     * @return int
     */
    public function getEdadMinima() {
        return $this->edadMinima;
    }

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return Division
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
     * Add tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     *
     * @return Division
     */
    public function addTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tiposReservaEscenarioDeportivo[] = $tipoReservaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo
     */
    public function removeTipoReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tipoReservaEscenarioDeportivo) {
        $this->tiposReservaEscenarioDeportivo->removeElement($tipoReservaEscenarioDeportivo);
    }

    /**
     * Get tiposReservaEscenarioDeportivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposReservaEscenarioDeportivo() {
        return $this->tiposReservaEscenarioDeportivo;
    }

    /**
     * Set categoriaDivision
     *
     * @param \LogicBundle\Entity\CategoriaDivision $categoriaDivision
     *
     * @return Division
     */
    public function setCategoriaDivision(\LogicBundle\Entity\CategoriaDivision $categoriaDivision = null) {
        $this->categoriaDivision = $categoriaDivision;
        return $this;
    }

    /**
     * Get categoriaDivision
     *
     * @return \LogicBundle\Entity\CategoriaDivision
     */
    public function getCategoriaDivision() {
        return $this->categoriaDivision;
    }

    /**
     * Add disciplinasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinaEscenarioDeportivo
     *
     * @return Division
     */
    public function addDisciplinasEscenarioDeportivo(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinaEscenarioDeportivo) {
        $this->disciplinasEscenarioDeportivo[] = $disciplinaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove disciplinasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinaEscenarioDeportivo
     */
    public function removeDisciplinasEscenarioDeportivo(\LogicBundle\Entity\DisciplinasEscenarioDeportivo $disciplinaEscenarioDeportivo) {
        $this->disciplinasEscenarioDeportivo->removeElement($disciplinaEscenarioDeportivo);
    }

    /**
     * Get disciplinasEscenarioDeportivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinasEscenarioDeportivo() {
        return $this->disciplinasEscenarioDeportivo;
    }

    /**
     * Add tendenciaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo
     *
     * @return Division
     */
    public function addTendenciaEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo) {
        $this->tendenciasEscenarioDeportivo[] = $tendenciaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tendenciaEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo
     */
    public function removeTendenciaEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciaEscenarioDeportivo) {
        $this->tendenciasEscenarioDeportivo->removeElement($tendenciaEscenarioDeportivo);
    }

    /**
     * Get disciplinasEscenarioDeportivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTendenciasEscenarioDeportivo() {
        return $this->tendenciasEscenarioDeportivo;
    }

    /**
     * Add tendenciasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciasEscenarioDeportivo
     *
     * @return Division
     */
    public function addTendenciasEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciasEscenarioDeportivo) {
        $this->tendenciasEscenarioDeportivo[] = $tendenciasEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tendenciasEscenarioDeportivo
     *
     * @param \LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciasEscenarioDeportivo
     */
    public function removeTendenciasEscenarioDeportivo(\LogicBundle\Entity\TendenciaEscenarioDeportivo $tendenciasEscenarioDeportivo) {
        $this->tendenciasEscenarioDeportivo->removeElement($tendenciasEscenarioDeportivo);
    }

    /**
     * Set disponibilidad
     *
     * @param boolean $disponibilidadDias
     *
     * @return Division
     */
    public function setDisponibilidadDias($disponibilidadDias) {
        $this->disponibilidadDias = $disponibilidadDias;

        return $this;
    }

    /**
     * Get disponibilidadDias
     *
     * @return boolean
     */
    public function getDisponibilidadDias() {
        return $this->disponibilidadDias;
    }

    /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Division
     */
    public function addEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     */
    public function removeEvento(\LogicBundle\Entity\Evento $evento) {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos() {
        return $this->eventos;
    }

    /**
     * Set necesitaAprobacion
     *
     * @param boolean $necesitaAprobacion
     *
     * @return InformacionExtraUsuario
     */
    public function setNecesitaAprobacion($necesitaAprobacion) {
        $this->necesitaAprobacion = $necesitaAprobacion;

        return $this;
    }

    /**
     * Get necesitaAprobacion
     *
     * @return boolean
     */
    public function getNecesitaAprobacion() {
        return $this->necesitaAprobacion;
    }

    ///*horario para la mañana*///

    /**
     * Set hora_inicial_lunes
     *
     * @param string $hora_inicial_lunes
     *
     * @return Division
     */
    public function setHoraInicialLunes($hora_inicial_lunes) {
        $this->hora_inicial_lunes = $hora_inicial_lunes;

        return $this;
    }

    /**
     * Get hora_inicial_lunes
     *
     * @return string
     */
    public function getHoraInicialLunes() {
        return $this->hora_inicial_lunes;
    }

    /**
     * Set hora_final_lunes
     *
     * @param string $hora_final_lunes
     *
     * @return Division
     */
    public function setHoraFinalLunes($hora_final_lunes) {
        $this->hora_final_lunes = $hora_final_lunes;

        return $this;
    }

    /**
     * Get hora_final_lunes
     *
     * @return string
     */
    public function getHoraFinalLunes() {
        return $this->hora_final_lunes;
    }

    /**
     * Set hora_inicial_martes
     *
     * @param string $hora_inicial_martes
     *
     * @return Division
     */
    public function setHoraInicialMartes($hora_inicial_martes) {
        $this->hora_inicial_martes = $hora_inicial_martes;

        return $this;
    }

    /**
     * Get hora_inicial_martes
     *
     * @return string
     */
    public function getHoraInicialMartes() {
        return $this->hora_inicial_martes;
    }

    /**
     * Set hora_final_martes
     *
     * @param string $hora_final_martes
     *
     * @return Division
     */
    public function setHoraFinalMartes($hora_final_martes) {
        $this->hora_final_martes = $hora_final_martes;

        return $this;
    }

    /**
     * Get hora_final_martes
     *
     * @return string
     */
    public function getHoraFinalMartes() {
        return $this->hora_final_martes;
    }

    /**
     * Set hora_inicial_miercoles
     *
     * @param string $hora_inicial_miercoles
     *
     * @return Division
     */
    public function setHoraInicialMiercoles($hora_inicial_miercoles) {
        $this->hora_inicial_miercoles = $hora_inicial_miercoles;

        return $this;
    }

    /**
     * Get hora_inicial_miercoles
     *
     * @return string
     */
    public function getHoraInicialMiercoles() {
        return $this->hora_inicial_miercoles;
    }

    /**
     * Set hora_final_miercoles
     *
     * @param string $hora_final_miercoles
     *
     * @return Division
     */
    public function setHoraFinalMiercoles($hora_final_miercoles) {
        $this->hora_final_miercoles = $hora_final_miercoles;

        return $this;
    }

    /**
     * Get hora_final_miercoles
     *
     * @return string
     */
    public function getHoraFinalMiercoles() {
        return $this->hora_final_miercoles;
    }

    /**
     * Set hora_inicial_jueves
     *
     * @param string $hora_inicial_jueves
     *
     * @return Division
     */
    public function setHoraInicialJueves($hora_inicial_jueves) {
        $this->hora_inicial_jueves = $hora_inicial_jueves;

        return $this;
    }

    /**
     * Get hora_inicial_miercoles
     *
     * @return string
     */
    public function getHoraInicialJueves() {
        return $this->hora_inicial_jueves;
    }

    /**
     * Set hora_final_jueves
     *
     * @param string $hora_final_jueves
     *
     * @return Division
     */
    public function setHoraFinalJueves($hora_final_jueves) {
        $this->hora_final_jueves = $hora_final_jueves;

        return $this;
    }

    /**
     * Get hora_final_jueves
     *
     * @return string
     */
    public function getHoraFinalJueves() {
        return $this->hora_final_jueves;
    }

    /**
     * Set hora_inicial_viernes
     *
     * @param string $hora_inicial_viernes
     *
     * @return Division
     */
    public function setHoraInicialViernes($hora_inicial_viernes) {
        $this->hora_inicial_viernes = $hora_inicial_viernes;

        return $this;
    }

    /**
     * Get hora_inicial_viernes
     *
     * @return string
     */
    public function getHoraInicialViernes() {
        return $this->hora_inicial_viernes;
    }

    /**
     * Set hora_final_viernes
     *
     * @param string $hora_final_viernes
     *
     * @return Division
     */
    public function setHoraFinalViernes($hora_final_viernes) {
        $this->hora_final_viernes = $hora_final_viernes;

        return $this;
    }

    /**
     * Get hora_final_viernes
     *
     * @return string
     */
    public function getHoraFinalViernes() {
        return $this->hora_final_viernes;
    }

    /**
     * Set hora_inicial_sabado
     *
     * @param string $hora_inicial_sabado
     *
     * @return Division
     */
    public function setHoraInicialSabado($hora_inicial_sabado) {
        $this->hora_inicial_sabado = $hora_inicial_sabado;

        return $this;
    }

    /**
     * Get hora_inicial_sabado
     *
     * @return string
     */
    public function getHoraInicialSabado() {
        return $this->hora_inicial_sabado;
    }

    /**
     * Set hora_final_sabado
     *
     * @param string $hora_final_sabado
     *
     * @return Division
     */
    public function setHoraFinalSabado($hora_final_sabado) {
        $this->hora_final_sabado = $hora_final_sabado;

        return $this;
    }

    /**
     * Get hora_final_sabado
     *
     * @return string
     */
    public function getHoraFinalSabado() {
        return $this->hora_final_sabado;
    }

    /**
     * Set hora_inicial_domingo
     *
     * @param string $hora_inicial_domingo
     *
     * @return Division
     */
    public function setHoraInicialDomingo($hora_inicial_domingo) {
        $this->hora_inicial_domingo = $hora_inicial_domingo;

        return $this;
    }

    /**
     * Get hora_inicial_domingo
     *
     * @return string
     */
    public function getHoraInicialDomingo() {
        return $this->hora_inicial_domingo;
    }

    /**
     * Set hora_final_domingo
     *
     * @param string $hora_final_domingo
     *
     * @return Division
     */
    public function setHoraFinalDomingo($hora_final_domingo) {
        $this->hora_final_domingo = $hora_final_domingo;

        return $this;
    }

    /**
     * Get hora_final_sabado
     *
     * @return string
     */
    public function getHoraFinalDomingo() {
        return $this->hora_final_domingo;
    }

    ///*horario para la tarde*///

    /**
     * Set hora_inicial2_lunes
     *
     * @param string $hora_inicial2_lunes
     *
     * @return Division
     */
    public function setHoraInicial2Lunes($hora_inicial2_lunes) {
        $this->hora_inicial2_lunes = $hora_inicial2_lunes;

        return $this;
    }

    /**
     * Get hora_inicial2_lunes
     *
     * @return string
     */
    public function getHoraInicial2Lunes() {
        return $this->hora_inicial2_lunes;
    }

    /**
     * Set hora_final2_lunes
     *
     * @param string $hora_final2_lunes
     *
     * @return Division
     */
    public function setHoraFinal2Lunes($hora_final2_lunes) {
        $this->hora_final2_lunes = $hora_final2_lunes;

        return $this;
    }

    /**
     * Get hora_final2_lunes
     *
     * @return string
     */
    public function getHoraFinal2Lunes() {
        return $this->hora_final2_lunes;
    }

    /**
     * Set hora_inicial2_martes
     *
     * @param string $hora_inicial_martes
     *
     * @return Division
     */
    public function setHoraInicial2Martes($hora_inicial2_martes) {
        $this->hora_inicial2_martes = $hora_inicial2_martes;

        return $this;
    }

    /**
     * Get hora_inicial2_martes
     *
     * @return string
     */
    public function getHoraInicial2Martes() {
        return $this->hora_inicial2_martes;
    }

    /**
     * Set hora_final2_martes
     *
     * @param string $hora_final2_martes
     *
     * @return Division
     */
    public function setHoraFinal2Martes($hora_final2_martes) {
        $this->hora_final2_martes = $hora_final2_martes;

        return $this;
    }

    /**
     * Get hora_final2_martes
     *
     * @return string
     */
    public function getHoraFinal2Martes() {
        return $this->hora_final2_martes;
    }

    /**
     * Set hora_inicial2_miercoles
     *
     * @param string $hora_inicial2_miercoles
     *
     * @return Division
     */
    public function setHoraInicial2Miercoles($hora_inicial2_miercoles) {
        $this->hora_inicial2_miercoles = $hora_inicial2_miercoles;

        return $this;
    }

    /**
     * Get hora_inicial2_miercoles
     *
     * @return string
     */
    public function getHoraInicial2Miercoles() {
        return $this->hora_inicial2_miercoles;
    }

    /**
     * Set hora_final2_miercoles
     *
     * @param string $hora_final2_miercoles
     *
     * @return Division
     */
    public function setHoraFinal2Miercoles($hora_final2_miercoles) {
        $this->hora_final2_miercoles = $hora_final2_miercoles;

        return $this;
    }

    /**
     * Get hora_final_miercoles
     *
     * @return string
     */
    public function getHoraFinal2Miercoles() {
        return $this->hora_final2_miercoles;
    }

    /**
     * Set hora_inicial2_jueves
     *
     * @param string $hora_inicial2_jueves
     *
     * @return Division
     */
    public function setHoraInicial2Jueves($hora_inicial2_jueves) {
        $this->hora_inicial2_jueves = $hora_inicial2_jueves;

        return $this;
    }

    /**
     * Get hora_inicial2_miercoles
     *
     * @return string
     */
    public function getHoraInicial2Jueves() {
        return $this->hora_inicial2_jueves;
    }

    /**
     * Set hora_final2_jueves
     *
     * @param string $hora_final2_jueves
     *
     * @return Division
     */
    public function setHoraFinal2Jueves($hora_final2_jueves) {
        $this->hora_final2_jueves = $hora_final2_jueves;

        return $this;
    }

    /**
     * Get hora_final2_jueves
     *
     * @return string
     */
    public function getHoraFinal2Jueves() {
        return $this->hora_final2_jueves;
    }

    /**
     * Set hora_inicial2_viernes
     *
     * @param string $hora_inicial2_viernes
     *
     * @return Division
     */
    public function setHoraInicial2Viernes($hora_inicial2_viernes) {
        $this->hora_inicial2_viernes = $hora_inicial2_viernes;

        return $this;
    }

    /**
     * Get hora_inicial2_viernes
     *
     * @return string
     */
    public function getHoraInicial2Viernes() {
        return $this->hora_inicial2_viernes;
    }

    /**
     * Set hora_final2_viernes
     *
     * @param string $hora_final2_viernes
     *
     * @return Division
     */
    public function setHoraFinal2Viernes($hora_final2_viernes) {
        $this->hora_final2_viernes = $hora_final2_viernes;

        return $this;
    }

    /**
     * Get hora_final2_viernes
     *
     * @return string
     */
    public function getHoraFinal2Viernes() {
        return $this->hora_final2_viernes;
    }

    /**
     * Set hora_inicial2_sabado
     *
     * @param string $hora_inicial2_sabado
     *
     * @return Division
     */
    public function setHoraInicial2Sabado($hora_inicial2_sabado) {
        $this->hora_inicial2_sabado = $hora_inicial2_sabado;

        return $this;
    }

    /**
     * Get hora_inicial2_sabado
     *
     * @return string
     */
    public function getHoraInicial2Sabado() {
        return $this->hora_inicial2_sabado;
    }

    /**
     * Set hora_final2_sabado
     *
     * @param string $hora_final2_sabado
     *
     * @return Division
     */
    public function setHoraFinal2Sabado($hora_final2_sabado) {
        $this->hora_final2_sabado = $hora_final2_sabado;

        return $this;
    }

    /**
     * Get hora_final2_sabado
     *
     * @return string
     */
    public function getHoraFinal2Sabado() {
        return $this->hora_final2_sabado;
    }

    /**
     * Set hora_inicial2_domingo
     *
     * @param string $hora_inicial2_domingo
     *
     * @return Division
     */
    public function setHoraInicial2Domingo($hora_inicial2_domingo) {
        $this->hora_inicial2_domingo = $hora_inicial2_domingo;

        return $this;
    }

    /**
     * Get hora_inicial2_domingo
     *
     * @return string
     */
    public function getHoraInicial2Domingo() {
        return $this->hora_inicial2_domingo;
    }

    /**
     * Set hora_final2_domingo
     *
     * @param string $hora_final2_domingo
     *
     * @return Division
     */
    public function setHoraFinal2Domingo($hora_final2_domingo) {
        $this->hora_final2_domingo = $hora_final2_domingo;

        return $this;
    }

    /**
     * Get hora_final2_sabado
     *
     * @return string
     */
    public function getHoraFinal2Domingo() {
        return $this->hora_final2_domingo;
    }

    /**
     * Add tiposReservaEscenarioDeportivo.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tiposReservaEscenarioDeportivo
     *
     * @return Division
     */
    public function addTiposReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tiposReservaEscenarioDeportivo) {
        $this->tiposReservaEscenarioDeportivo[] = $tiposReservaEscenarioDeportivo;

        return $this;
    }

    /**
     * Remove tiposReservaEscenarioDeportivo.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivo $tiposReservaEscenarioDeportivo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTiposReservaEscenarioDeportivo(\LogicBundle\Entity\TipoReservaEscenarioDeportivo $tiposReservaEscenarioDeportivo) {
        return $this->tiposReservaEscenarioDeportivo->removeElement($tiposReservaEscenarioDeportivo);
    }

    /**
     * Add tipoReservaEscenarioDeportivoDivisione.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione
     *
     * @return Division
     */
    public function addTipoReservaEscenarioDeportivoDivisione(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione) {
        $this->tipoReservaEscenarioDeportivoDivisiones[] = $tipoReservaEscenarioDeportivoDivisione;

        return $this;
    }

    /**
     * Remove tipoReservaEscenarioDeportivoDivisione.
     *
     * @param \LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTipoReservaEscenarioDeportivoDivisione(\LogicBundle\Entity\TipoReservaEscenarioDeportivoDivision $tipoReservaEscenarioDeportivoDivisione) {
        return $this->tipoReservaEscenarioDeportivoDivisiones->removeElement($tipoReservaEscenarioDeportivoDivisione);
    }

    /**
     * Add divisione.
     *
     * @param \LogicBundle\Entity\OfertaDivision $divisione
     *
     * @return Division
     */
    public function addDivisione(\LogicBundle\Entity\OfertaDivision $divisione) {
        $this->divisiones[] = $divisione;

        return $this;
    }

    /**
     * Remove divisione.
     *
     * @param \LogicBundle\Entity\OfertaDivision $divisione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDivisione(\LogicBundle\Entity\OfertaDivision $divisione) {
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
     * Add reserva.
     *
     * @param \LogicBundle\Entity\DivisionReserva $reserva
     *
     * @return Division
     */
    public function addReserva(\LogicBundle\Entity\DivisionReserva $reserva) {
        $this->reservas[] = $reserva;

        return $this;
    }

    /**
     * Remove reserva.
     *
     * @param \LogicBundle\Entity\DivisionReserva $reserva
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeReserva(\LogicBundle\Entity\DivisionReserva $reserva) {
        return $this->reservas->removeElement($reserva);
    }

    /**
     * Get reservas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservas() {
        return $this->reservas;
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
     * Add bloqueo.
     *
     * @param \LogicBundle\Entity\DivisionBloqueo $bloqueo
     *
     * @return Division
     */
    public function addBloqueo(\LogicBundle\Entity\DivisionBloqueo $bloqueo)
    {
        $this->bloqueos[] = $bloqueo;

        return $this;
    }

    /**
     * Remove bloqueo.
     *
     * @param \LogicBundle\Entity\DivisionBloqueo $bloqueo
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBloqueo(\LogicBundle\Entity\DivisionBloqueo $bloqueo)
    {
        return $this->bloqueos->removeElement($bloqueo);
    }

    /**
     * Get bloqueos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBloqueos()
    {
        return $this->bloqueos;
    }
}
