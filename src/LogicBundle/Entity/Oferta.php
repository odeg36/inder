<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Oferta
 * @Gedmo\Loggable
 * @ORM\Table(name="oferta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OfertaRepository")
 */
class Oferta {

    ////************ MODIFICACIONES ********////////////

    const REGISTRO_MANUAL = 'registroManual';
    const REGISTRO_EXCEL = 'registroExcel';

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    public function ofertaDisponible(User $usuario, ContainerInterface $container, $esFormador = false) {
        $activa = "";
        //  OFERTA ACTIVA
        if (!$this->getActivo()) {
            $activa = "inactiva";
        }
        //  OFERTA EN RANGO DE FECHAS
        $fechaActual = new \DateTime();
        $fechaInicial = $this->getFechaInicioPreinscripcion() ?: $this->getFechaInicial();
        $fechaFinal = $this->getFechaFinalPreinscripcion() ?: $this->getFechaFinal();
        if ($fechaActual < $fechaInicial || $fechaActual >= $fechaFinal->add(new \DateInterval('PT23H59M59S'))) {
            $activa = "fecha_rango";
        }
        //  OFERTA CON CUPO
        $cupoMaximo = $this->getEstrategia()->getCoberturaGeneralMax();
        if ($this->getTendenciaEstrategia()) {
            $cupoMaximo = $this->getTendenciaEstrategia()->getCoberturaMaxima();
        }
        if ($this->getDisciplinaEstrategia()) {
            $cupoMaximo = $this->getDisciplinaEstrategia()->getCoberturaMaxima();
        }
        if ($this->getInstitucionalEstrategia()) {
            $cupoMaximo = $this->getInstitucionalEstrategia()->getCoberturaMaxima();
        }
        if (count($this->getPreinscritos()) >= $cupoMaximo) {
            $activa = "cupo_lleno";
        }
        return $activa;
    }

    /**
     * @var int
     * @Gedmo\Versioned
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Estrategia", inversedBy="ofertas");
     * @ORM\JoinColumn(name="estrategia_id", referencedColumnName="id",  nullable=false)
     */
    private $estrategia;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\DisciplinaEstrategia", inversedBy="ofertas")
     * @ORM\JoinColumn(name="disciplina_estrategia_id", referencedColumnName="id", nullable=true)
     */
    private $disciplinaEstrategia;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\TendenciaEstrategia", inversedBy="ofertas")
     * @ORM\JoinColumn(name="tendencia_estrategia_id", referencedColumnName="id", nullable=true)
     */
    private $tendenciaEstrategia;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\InstitucionalEstrategia", inversedBy="ofertas")
     * @ORM\JoinColumn(name="institucional_estrategia_id", referencedColumnName="id", nullable=true)
     */
    private $institucionalEstrategia;

    /**
     * @ORM\ManyToOne(targetEntity="FuenteFinanciacion", inversedBy="ofertas");
     * @ORM\JoinColumn(name="fuentefinanciacion_id", referencedColumnName="id")
     */
    private $fuenteFinanciacion;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="ofertaFormador");
     * @ORM\JoinColumn(name="formador_id", referencedColumnName="id")
     */
    private $formador;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="ofertaGestor");
     * @ORM\JoinColumn(name="gestor_id", referencedColumnName="id")
     */
    private $gestor;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="oferta");
     * @ORM\JoinColumn(name="escenariodeportivo_id", referencedColumnName="id")
     */
    private $escenarioDeportivo;
   
    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="PuntoAtencion", inversedBy="oferta");
     * @ORM\JoinColumn(name="puntoatencion_id", referencedColumnName="id")
     */
    private $puntoAtencion;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Programacion", mappedBy="oferta", cascade={"persist", "remove"});
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $programacion;

    /**
     * @ORM\OneToMany(targetEntity="PreinscripcionOferta", mappedBy="oferta")
     */
    private $preinscritos;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\PlanAnualMetodologico", inversedBy="ofertas");
     * @ORM\JoinColumn(name="planAnualMetodologico_id", referencedColumnName="id")
     */
    private $planAnualMetodologico;
    
    /**
     * @ORM\OneToMany(targetEntity="OfertaDivision", mappedBy="oferta", cascade={"persist"},  orphanRemoval=true)
     */
    private $divisiones;


    /**
     * @var date
     * @Gedmo\Versioned
     * @ORM\Column(name="fecha_inicial", type="date")
     */
    private $fecha_inicial;

    /**
     * @var date
     *  @Gedmo\Versioned
     * @ORM\Column(name="fecha_final", type="date")
     */
    private $fecha_final;

    /**
     * @var date
     * @Gedmo\Versioned
     * @ORM\Column(name="fecha_inicio_preinscripcion", type="date",nullable=true)
     */
    private $fecha_inicio_preinscripcion;

    /**
     * @var date
     *  @Gedmo\Versioned
     * @ORM\Column(name="fecha_final_preinscripcion", type="date",nullable=true)
     */
    private $fecha_final_preinscripcion;

    /**
     * @var bool
     * @Gedmo\Versioned
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Asistencia", mappedBy="oferta")
     */
    private $asistencias;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text",nullable=true)
     */
    private $descripcion;

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PlanClase", mappedBy="oferta");
     */
    private $planesClase;

    /**
     * Add preinscrito
     *
     * @param \LogicBundle\Entity\PreinscripcionOferta $preinscrito
     *
     * @return Oferta
     */
    public function addPreinscrito(\LogicBundle\Entity\PreinscripcionOferta $preinscrito) {
        $preinscrito->setOferta($this);
        $this->preinscritos[] = $preinscrito;

        return $this;
    }

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Encuesta", mappedBy="oferta");
     */
    private $encuestas;

    /**
     * Add programacion
     *
     * @param \LogicBundle\Entity\Programacion $programacion
     *
     * @return Oferta
     */
    public function addProgramacion(\LogicBundle\Entity\Programacion $programacion) {
        $programacion->setOferta($this);
        $this->programacion[] = $programacion;

        return $this;
    }

    /**
     * Get imagenPerfil
     *
     * @return string
     */
    public function getImagenPerfil() {
        if (!$this->imagen_perfil) {
            $this->imagen_perfil = "carousel1.jpg";
        }
        return $this->imagen_perfil;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
        }

        $filename = $file->getClientOriginalName();

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = uniqid(date('YmdHis')) . '.' . $ext;
        $file->move(
                $path, $filename
        );

        return $filename;
    }

    ////************ FIN MODIFICACIONES ********////////////

    /**
     * Constructor
     */
    public function __construct() {
        $this->programacion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preinscritos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asistencias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuestas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planesClase = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Oferta
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
     * Set fechaInicial
     *
     * @param \DateTime $fechaInicial
     *
     * @return Oferta
     */
    public function setFechaInicial($fechaInicial) {
        $this->fecha_inicial = $fechaInicial;

        return $this;
    }

    /**
     * Get fechaInicial
     *
     * @return \DateTime
     */
    public function getFechaInicial() {
        return $this->fecha_inicial;
    }

    /**
     * Set fechaFinal
     *
     * @param \DateTime $fechaFinal
     *
     * @return Oferta
     */
    public function setFechaFinal($fechaFinal) {
        $this->fecha_final = $fechaFinal;

        return $this;
    }

    /**
     * Get fechaFinal
     *
     * @return \DateTime
     */
    public function getFechaFinal() {
        return $this->fecha_final;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Oferta
     */
    public function setActivo($activo) {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Oferta
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
     * @return Oferta
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
     * Set estrategia
     *
     * @param \LogicBundle\Entity\Estrategia $estrategia
     *
     * @return Oferta
     */
    public function setEstrategia(\LogicBundle\Entity\Estrategia $estrategia = null) {
        $this->estrategia = $estrategia;

        return $this;
    }

    /**
     * Get estrategia
     *
     * @return \LogicBundle\Entity\Estrategia
     */
    public function getEstrategia() {
        return $this->estrategia;
    }

    /**
     * Set disciplinaEstrategia
     *
     * @param \LogicBundle\Entity\DisciplinaEstrategia $disciplinaEstrategia
     *
     * @return Oferta
     */
    public function setDisciplinaEstrategia(\LogicBundle\Entity\DisciplinaEstrategia $disciplinaEstrategia = null) {
        $this->disciplinaEstrategia = $disciplinaEstrategia;

        return $this;
    }

    /**
     * Get disciplinaEstrategia
     *
     * @return \LogicBundle\Entity\DisciplinaEstrategia
     */
    public function getDisciplinaEstrategia() {
        return $this->disciplinaEstrategia;
    }

    /**
     * Set tendenciaEstrategia
     *
     * @param \LogicBundle\Entity\TendenciaEstrategia $tendenciaEstrategia
     *
     * @return Oferta
     */
    public function setTendenciaEstrategia(\LogicBundle\Entity\TendenciaEstrategia $tendenciaEstrategia = null) {
        $this->tendenciaEstrategia = $tendenciaEstrategia;

        return $this;
    }

    /**
     * Get tendenciaEstrategia
     *
     * @return \LogicBundle\Entity\TendenciaEstrategia
     */
    public function getTendenciaEstrategia() {
        return $this->tendenciaEstrategia;
    }

    /**
     * Set institucionalEstrategia
     *
     * @param \LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia
     *
     * @return Oferta
     */
    public function setInstitucionalEstrategia(\LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia = null) {
        $this->institucionalEstrategia = $institucionalEstrategia;

        return $this;
    }

    /**
     * Get institucionalEstrategia
     *
     * @return \LogicBundle\Entity\InstitucionalEstrategia
     */
    public function getInstitucionalEstrategia() {
        return $this->institucionalEstrategia;
    }

    /**
     * Set fuenteFinanciacion
     *
     * @param \LogicBundle\Entity\FuenteFinanciacion $fuenteFinanciacion
     *
     * @return Oferta
     */
    public function setFuenteFinanciacion(\LogicBundle\Entity\FuenteFinanciacion $fuenteFinanciacion = null) {
        $this->fuenteFinanciacion = $fuenteFinanciacion;

        return $this;
    }

    /**
     * Get fuenteFinanciacion
     *
     * @return \LogicBundle\Entity\FuenteFinanciacion
     */
    public function getFuenteFinanciacion() {
        return $this->fuenteFinanciacion;
    }

    /**
     * Set formador
     *
     * @param \Application\Sonata\UserBundle\Entity\User $formador
     *
     * @return Oferta
     */
    public function setFormador(\Application\Sonata\UserBundle\Entity\User $formador = null) {
        $this->formador = $formador;

        return $this;
    }

    /**
     * Get formador
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getFormador() {
        return $this->formador;
    }

    /**
     * Set gestor
     *
     * @param \Application\Sonata\UserBundle\Entity\User $gestor
     *
     * @return Oferta
     */
    public function setGestor(\Application\Sonata\UserBundle\Entity\User $gestor = null) {
        $this->gestor = $gestor;

        return $this;
    }

    /**
     * Get gestor
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getGestor() {
        return $this->gestor;
    }

    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return Oferta
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
     * Set puntoAtencion
     *
     * @param \LogicBundle\Entity\PuntoAtencion $puntoAtencion
     *
     * @return Oferta
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
     * Remove preinscrito
     *
     * @param \LogicBundle\Entity\PreinscripcionOferta $preinscrito
     */
    public function removePreinscrito(\LogicBundle\Entity\PreinscripcionOferta $preinscrito) {
        $this->preinscritos->removeElement($preinscrito);
    }

    /**
     * Get preinscritos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreinscritos() {
        return $this->preinscritos;
    }

    /**
     * Set planAnualMetodologico
     *
     * @param \LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico
     *
     * @return Oferta
     */
    public function setPlanAnualMetodologico(\LogicBundle\Entity\PlanAnualMetodologico $planAnualMetodologico = null) {
        $this->planAnualMetodologico = $planAnualMetodologico;

        return $this;
    }

    /**
     * Get planAnualMetodologico
     *
     * @return \LogicBundle\Entity\PlanAnualMetodologico
     */
    public function getPlanAnualMetodologico() {
        return $this->planAnualMetodologico;
    }

    /**
     * Add asistencia
     *
     * @param \LogicBundle\Entity\Asistencia $asistencia
     *
     * @return Oferta
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

    /**
     * Add encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $encuesta
     *
     * @return Oferta
     */
    public function addEncuesta(\LogicBundle\Entity\Encuesta $encuesta) {
        $this->encuestas[] = $encuesta;

        return $this;
    }

    /**
     * Remove encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $encuesta
     */
    public function removeEncuesta(\LogicBundle\Entity\Encuesta $encuesta) {
        $this->encuestas->removeElement($encuesta);
    }

    /**
     * Get encuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestas() {
        return $this->encuestas;
    }

    /**
     * Set imagen.
     *
     * @param string|null $imagen
     *
     * @return Oferta
     */
    public function setImagen($imagen = null) {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen.
     *
     * @return string|null
     */
    public function getImagen() {
        return $this->imagen;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Oferta
     */
    public function setDescripcion($descripcion = null) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set fechaInicioPreinscripcion.
     *
     * @param \DateTime $fechaInicioPreinscripcion
     *
     * @return Oferta
     */
    public function setFechaInicioPreinscripcion($fechaInicioPreinscripcion) {
        $this->fecha_inicio_preinscripcion = $fechaInicioPreinscripcion;

        return $this;
    }

    /**
     * Get fechaInicioPreinscripcion.
     *
     * @return \DateTime
     */
    public function getFechaInicioPreinscripcion() {
        return $this->fecha_inicio_preinscripcion;
    }

    /**
     * Set fechaFinalPreinscripcion.
     *
     * @param \DateTime $fechaFinalPreinscripcion
     *
     * @return Oferta
     */
    public function setFechaFinalPreinscripcion($fechaFinalPreinscripcion) {
        $this->fecha_final_preinscripcion = $fechaFinalPreinscripcion;

        return $this;
    }

    /**
     * Get fechaFinalPreinscripcion.
     *
     * @return \DateTime
     */
    public function getFechaFinalPreinscripcion() {
        return $this->fecha_final_preinscripcion;
    }

    /**
     * Add planClase
     *
     * @param \LogicBundle\Entity\PlanClase $planClase
     *
     * @return Oferta
     */
    public function addPlanClase(\LogicBundle\Entity\PlanClase $planClase) {
        $this->planesClase[] = $planClase;

        return $this;
    }

    /**
     * Remove planClase
     *
     * @param \LogicBundle\Entity\PlanClase $planClase
     */
    public function removePlanClase(\LogicBundle\Entity\PlanClase $planClase) {
        $this->planesClase->removeElement($planClase);
    }

    /**
     * Get planesClase
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanesClase() {
        return $this->planesClase;
    }

    /**
     * Add planesClase.
     *
     * @param \LogicBundle\Entity\PlanClase $planesClase
     *
     * @return Oferta
     */
    public function addPlanesClase(\LogicBundle\Entity\PlanClase $planesClase) {
        $this->planesClase[] = $planesClase;

        return $this;
    }

    /**
     * Remove planesClase.
     *
     * @param \LogicBundle\Entity\PlanClase $planesClase
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanesClase(\LogicBundle\Entity\PlanClase $planesClase) {
        return $this->planesClase->removeElement($planesClase);
    }


    /**
     * Add divisione.
     *
     * @param \LogicBundle\Entity\OfertaDivision $divisione
     *
     * @return Oferta
     */
    public function addDivisione(\LogicBundle\Entity\OfertaDivision $divisione)
    {
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
    public function removeDivisione(\LogicBundle\Entity\OfertaDivision $divisione)
    {
        return $this->divisiones->removeElement($divisione);
    }

    /**
     * Get divisiones.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisiones()
    {
        return $this->divisiones;
    }
}
