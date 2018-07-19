<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TestControlFisioterapia
 *
 * @ORM\Table(name="test_control_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TestControlFisioterapiaRepository")
 */
class TestControlFisioterapia {

    const NOMBRE_ENTRENAMIENTO_FMS = 'Screening funcional del movimiento "FMS"';
    const NOMBRE_ENTRENAMIENTO_CORE = 'Test de Core';

    public function __toString() {
        return (string) $this->getFisioterapia()->getDeportista() ?: '';
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
     * @var \DateTime $fecha_inicio
     * @ORM\Column(name="fecha_inicio", type="date")
     */
    protected $fechaInicio;

    /**
     * @var \DateTime $fecha_inicio
     * @ORM\Column(name="fecha_fin", type="date")
     */
    protected $fechaFin;

    /**
     * @var string
     *
     * @ORM\Column(name="fase_entrenamiento", type="string", length=255, nullable=true)
     */
    private $faseEntrenamiento;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_entrenamiento", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="PruebaControlFisioterapia", mappedBy="testControlFisioterapia", cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $pruebaTestControlFisioterapias;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaFisioterapia", inversedBy="testControls")
     * @ORM\JoinColumn(name="fisioterapia_id", referencedColumnName="id")
     */
    private $fisioterapia;

    /**
     * Add pruebaTestControlFisioterapia
     *
     * @param \LogicBundle\Entity\PruebaControlFisioterapia $pruebaTestControlFisioterapia
     *
     * @return TestControlFisioterapia
     */
    public function addPruebaTestControlFisioterapia(\LogicBundle\Entity\PruebaControlFisioterapia $pruebaTestControlFisioterapia) {
        $pruebaTestControlFisioterapia->setTestControlFisioterapia($this);
        $this->pruebaTestControlFisioterapias[] = $pruebaTestControlFisioterapia;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////
    /**
     * Constructor
     */
    public function __construct() {
        $this->pruebaTestControlFisioterapias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return TestControlFisioterapia
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
     * @return TestControlFisioterapia
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return TestControlFisioterapia
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
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return TestControlFisioterapia
     */
    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin() {
        return $this->fechaFin;
    }

    /**
     * Set faseEntrenamiento
     *
     * @param string $faseEntrenamiento
     *
     * @return TestControlFisioterapia
     */
    public function setFaseEntrenamiento($faseEntrenamiento) {
        $this->faseEntrenamiento = $faseEntrenamiento;

        return $this;
    }

    /**
     * Get faseEntrenamiento
     *
     * @return string
     */
    public function getFaseEntrenamiento() {
        return $this->faseEntrenamiento;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TestControlFisioterapia
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return TestControlFisioterapia
     */
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones() {
        return $this->observaciones;
    }

    /**
     * Remove pruebaTestControlFisioterapia
     *
     * @param \LogicBundle\Entity\PruebaControlFisioterapia $pruebaTestControlFisioterapia
     */
    public function removePruebaTestControlFisioterapia(\LogicBundle\Entity\PruebaControlFisioterapia $pruebaTestControlFisioterapia) {
        $this->pruebaTestControlFisioterapias->removeElement($pruebaTestControlFisioterapia);
    }

    /**
     * Get pruebaTestControlFisioterapias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebaTestControlFisioterapias() {
        return $this->pruebaTestControlFisioterapias;
    }

    /**
     * Set fisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $fisioterapia
     *
     * @return TestControlFisioterapia
     */
    public function setFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $fisioterapia = null) {
        $this->fisioterapia = $fisioterapia;

        return $this;
    }

    /**
     * Get fisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getFisioterapia() {
        return $this->fisioterapia;
    }

}
