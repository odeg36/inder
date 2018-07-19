<?php

namespace LogicBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Estrategia
 *
 * @ORM\Table(name="estrategia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EstrategiaRepository")
 * @UniqueEntity("nombre")
 */
class Estrategia {

    //// ***************    MODIFICACIONES     ************* /////
    // METODOS MODIFICADOS --->

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * Add disciplina
     *
     * @param DisciplinaEstrategia $disciplina
     *
     * @return Estrategia
     */
    public function addDisciplina(DisciplinaEstrategia $disciplina) {
        $disciplina->setEstrategia($this);
        $this->disciplinas[] = $disciplina;

        return $this;
    }

    /**
     * Add tendencia
     *
     * @param \LogicBundle\Entity\TendenciaEstrategia $tendencia
     *
     * @return Estrategia
     */
    public function addTendencia(\LogicBundle\Entity\TendenciaEstrategia $tendencia) {
        $tendencia->setEstrategia($this);
        $this->tendencias[] = $tendencia;

        return $this;
    }

    /**
     * Add institucionalEstrategia
     *
     * @param \LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia
     *
     * @return Estrategia
     */
    public function addInstitucionalEstrategia(\LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia) {
        $institucionalEstrategia->setEstrategia($this);
        $this->institucionalEstrategias[] = $institucionalEstrategia;

        return $this;
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
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="estrategias");
     * @ORM\JoinColumn(name="proyecto_id",referencedColumnName="id")
     */
    private $proyecto;

    /**
     * @ORM\ManyToOne(targetEntity="Segmentacion", inversedBy="estrategias");
     * @ORM\JoinColumn(name="segmentacion_id",referencedColumnName="id")
     */
    private $segmentacion;

    /**
     * @ORM\OneToMany(targetEntity="DisciplinaEstrategia", mappedBy="estrategia", cascade={"persist"}, orphanRemoval=true)
     */
    private $disciplinas;

    /**
     * @ORM\OneToMany(targetEntity="TendenciaEstrategia", mappedBy="estrategia", cascade={"persist"}, orphanRemoval=true)
     */
    private $tendencias;

    /**
     * @ORM\OneToMany(targetEntity="InstitucionalEstrategia", mappedBy="estrategia", cascade={"persist"}, orphanRemoval=true)
     */
    private $institucionalEstrategias;

    /**
     * @var int
     *
     * @ORM\Column(name="cobertura_general_min", type="integer")
     */
    private $coberturaGeneralMin;

    /**
     * @var int
     *
     * @ORM\Column(name="cobertura_general_max", type="integer")
     */
    private $coberturaGeneralMax;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text",nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="corteAsistencia", type="text", nullable = true)
     */
    private $corteAsistencia;

    /**
     * @var int
     *
     * @ORM\Column(name="plazoAdicional", type="integer",nullable=true)
     */
    private $plazoAdicional;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="acompanantes", type="boolean", nullable=true)
     */
    private $acompanantes;

    /**
     * @var bool
     *
     * @ORM\Column(name="diagnostico", type="boolean", nullable=true)
     */
    private $diagnostico;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Oferta", mappedBy="estrategia");
     */
    private $ofertas;

    /**
     * @var DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;

    /**
     * @ORM\OneToMany(targetEntity="EstrategiaCampo", mappedBy="estrategia", cascade={"persist", "remove"})
     */
    private $estrategiaCampos;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Area", inversedBy="estrategias")
     * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     */
    private $area;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Evento", mappedBy="estrategia")
     */
    private $eventos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Encuesta", mappedBy="estrategia");
     */
    private $encuestas;

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Constructor
     */
    public function __construct() {
        $this->disciplinas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tendencias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->institucionalEstrategias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->estrategiaCampos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->encuestas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Estrategia
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
     * Set coberturaGeneralMin
     *
     * @param integer $coberturaGeneralMin
     *
     * @return Estrategia
     */
    public function setCoberturaGeneralMin($coberturaGeneralMin) {
        $this->coberturaGeneralMin = $coberturaGeneralMin;

        return $this;
    }

    /**
     * Get coberturaGeneralMin
     *
     * @return integer
     */
    public function getCoberturaGeneralMin() {
        return $this->coberturaGeneralMin;
    }

    /**
     * Set coberturaGeneralMax
     *
     * @param integer $coberturaGeneralMax
     *
     * @return Estrategia
     */
    public function setCoberturaGeneralMax($coberturaGeneralMax) {
        $this->coberturaGeneralMax = $coberturaGeneralMax;

        return $this;
    }

    /**
     * Get coberturaGeneralMax
     *
     * @return integer
     */
    public function getCoberturaGeneralMax() {
        return $this->coberturaGeneralMax;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Estrategia
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set corteAsistencia
     *
     * @param string $corteAsistencia
     *
     * @return Estrategia
     */
    public function setCorteAsistencia($corteAsistencia) {
        $this->corteAsistencia = $corteAsistencia;

        return $this;
    }

    /**
     * Get corteAsistencia
     *
     * @return string
     */
    public function getCorteAsistencia() {
        return $this->corteAsistencia;
    }

    /**
     * Set plazoAdicional
     *
     * @param integer $plazoAdicional
     *
     * @return Estrategia
     */
    public function setPlazoAdicional($plazoAdicional) {
        $this->plazoAdicional = $plazoAdicional;

        return $this;
    }

    /**
     * Get plazoAdicional
     *
     * @return integer
     */
    public function getPlazoAdicional() {
        return $this->plazoAdicional;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Estrategia
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
     * Set acompanantes
     *
     * @param boolean $acompanantes
     *
     * @return Estrategia
     */
    public function setAcompanantes($acompanantes) {
        $this->acompanantes = $acompanantes;

        return $this;
    }

    /**
     * Get acompanantes
     *
     * @return boolean
     */
    public function getAcompanantes() {
        return $this->acompanantes;
    }

    /**
     * Set diagnostico
     *
     * @param boolean $diagnostico
     *
     * @return Estrategia
     */
    public function setDiagnostico($diagnostico) {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return boolean
     */
    public function getDiagnostico() {
        return $this->diagnostico;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Estrategia
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
     * @return Estrategia
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
     * Set proyecto
     *
     * @param \LogicBundle\Entity\Proyecto $proyecto
     *
     * @return Estrategia
     */
    public function setProyecto(\LogicBundle\Entity\Proyecto $proyecto = null) {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \LogicBundle\Entity\Proyecto
     */
    public function getProyecto() {
        return $this->proyecto;
    }

    /**
     * Set segmentacion
     *
     * @param \LogicBundle\Entity\Segmentacion $segmentacion
     *
     * @return Estrategia
     */
    public function setSegmentacion(\LogicBundle\Entity\Segmentacion $segmentacion = null) {
        $this->segmentacion = $segmentacion;

        return $this;
    }

    /**
     * Get segmentacion
     *
     * @return \LogicBundle\Entity\Segmentacion
     */
    public function getSegmentacion() {
        return $this->segmentacion;
    }

    /**
     * Remove disciplina
     *
     * @param \LogicBundle\Entity\DisciplinaEstrategia $disciplina
     */
    public function removeDisciplina(\LogicBundle\Entity\DisciplinaEstrategia $disciplina) {
        $this->disciplinas->removeElement($disciplina);
    }

    /**
     * Get disciplinas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinas() {
        return $this->disciplinas;
    }

    /**
     * Remove tendencia
     *
     * @param \LogicBundle\Entity\TendenciaEstrategia $tendencia
     */
    public function removeTendencia(\LogicBundle\Entity\TendenciaEstrategia $tendencia) {
        $this->tendencias->removeElement($tendencia);
    }

    /**
     * Get tendencias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTendencias() {
        return $this->tendencias;
    }

    /**
     * Remove institucionalEstrategia
     *
     * @param \LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia
     */
    public function removeInstitucionalEstrategia(\LogicBundle\Entity\InstitucionalEstrategia $institucionalEstrategia) {
        $this->institucionalEstrategias->removeElement($institucionalEstrategia);
    }

    /**
     * Get institucionalEstrategias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInstitucionalEstrategias() {
        return $this->institucionalEstrategias;
    }

    /**
     * Add oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return Estrategia
     */
    public function addOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas[] = $oferta;

        return $this;
    }

    /**
     * Remove oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     */
    public function removeOferta(\LogicBundle\Entity\Oferta $oferta) {
        $this->ofertas->removeElement($oferta);
    }

    /**
     * Get ofertas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOfertas() {
        return $this->ofertas;
    }

    /**
     * Add estrategiaCampo
     *
     * @param \LogicBundle\Entity\EstrategiaCampo $estrategiaCampo
     *
     * @return Estrategia
     */
    public function addEstrategiaCampo(\LogicBundle\Entity\EstrategiaCampo $estrategiaCampo) {
        $this->estrategiaCampos[] = $estrategiaCampo;

        return $this;
    }

    /**
     * Remove estrategiaCampo
     *
     * @param \LogicBundle\Entity\EstrategiaCampo $estrategiaCampo
     */
    public function removeEstrategiaCampo(\LogicBundle\Entity\EstrategiaCampo $estrategiaCampo) {
        $this->estrategiaCampos->removeElement($estrategiaCampo);
    }

    /**
     * Get estrategiaCampos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstrategiaCampos() {
        return $this->estrategiaCampos;
    }

    /**
     * Set area
     *
     * @param \LogicBundle\Entity\Area $area
     *
     * @return Estrategia
     */
    public function setArea(\LogicBundle\Entity\Area $area = null) {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \LogicBundle\Entity\Area
     */
    public function getArea() {
        return $this->area;
    }

    /**
     * Add evento
     *
     * @param \LogicBundle\Entity\Evento $evento
     *
     * @return Estrategia
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
     * Add encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $encuesta
     *
     * @return Estrategia
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

}
