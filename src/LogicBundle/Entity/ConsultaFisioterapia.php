<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ConsultaFisioterapia
 *
 * @ORM\Table(name="consulta_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ConsultaFisioterapiaRepository")
 */
class ConsultaFisioterapia {

    public function __toString() {
        return $this->getFechaCreacion()->format("d/m/Y") . ' - ' . $this->getDeportista();
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="consultasFisioterapia")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;

    /**
     * @ORM\OneToOne(targetEntity="ExamenFisico", mappedBy="consultaFisioterapia")
     */
    private $examenFisico;

    /**
     * @ORM\OneToOne(targetEntity="Anamnesis", mappedBy="consultaFisioterapia")
     */
    private $anamnesis;

    /**
     * @ORM\OneToMany(targetEntity="EvaluacionPosicion", mappedBy="consultaFisioterapia")
     */
    private $evaluacionesPosicion;

    /**
     * @ORM\OneToOne(targetEntity="EstabilidadLumboPelvica", mappedBy="consultaFisioterapia")
     */
    private $estabilidadLumboPelvica;

    /**
     * @ORM\OneToOne(targetEntity="SetAndSearch", mappedBy="consultaFisioterapia")
     */
    private $setAndSearch;

    /**
     * @ORM\OneToOne(targetEntity="SentadillaArranque", mappedBy="consultaFisioterapia")
     */
    private $sentadillaArranque;

    /**
     * @ORM\OneToOne(targetEntity="Postura", mappedBy="consultaFisioterapia")
     */
    private $postura;

    /**
     * @ORM\OneToOne(targetEntity="MedidaMMIBalance", mappedBy="consultaFisioterapia")
     */
    private $medidaMMIBalance;
    
    /**
     * @ORM\OneToMany(targetEntity="FichaCampoFisioterapia", mappedBy="fisioterapia")
     */
    private $fichaCampoFisioterapias;
    
    /**
     * @ORM\OneToMany(targetEntity="NotaFisioterapia", mappedBy="fisioterapia")
     */
    private $notaFisioterapias;
    
    /**
     * @ORM\OneToMany(targetEntity="TestControlFisioterapia", mappedBy="fisioterapia")
     */
    private $testControls;

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
     * @var bool
     *
     * @ORM\Column(name="finalizado", type="boolean", nullable=true)
     */
    protected $finalizado;

    /**
     * Constructor
     */
    public function __construct() {
        $this->evaluacionesPosicion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ConsultaFisioterapia
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null) {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista() {
        return $this->deportista;
    }

    /**
     * Set examenFisico
     *
     * @param \LogicBundle\Entity\ExamenFisico $examenFisico
     *
     * @return ConsultaFisioterapia
     */
    public function setExamenFisico(\LogicBundle\Entity\ExamenFisico $examenFisico = null) {
        $this->examenFisico = $examenFisico;

        return $this;
    }

    /**
     * Get examenFisico
     *
     * @return \LogicBundle\Entity\ExamenFisico
     */
    public function getExamenFisico() {
        return $this->examenFisico;
    }

    /**
     * Set anamnesis
     *
     * @param \LogicBundle\Entity\Anamnesis $anamnesis
     *
     * @return ConsultaFisioterapia
     */
    public function setAnamnesis(\LogicBundle\Entity\Anamnesis $anamnesis = null) {
        $this->anamnesis = $anamnesis;

        return $this;
    }

    /**
     * Get anamnesis
     *
     * @return \LogicBundle\Entity\Anamnesis
     */
    public function getAnamnesis() {
        return $this->anamnesis;
    }

    /**
     * Add evaluacionesPosicion
     *
     * @param \LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion
     *
     * @return ConsultaFisioterapia
     */
    public function addEvaluacionesPosicion(\LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion) {
        $this->evaluacionesPosicion[] = $evaluacionesPosicion;

        return $this;
    }

    /**
     * Remove evaluacionesPosicion
     *
     * @param \LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion
     */
    public function removeEvaluacionesPosicion(\LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion) {
        $this->evaluacionesPosicion->removeElement($evaluacionesPosicion);
    }

    /**
     * Get evaluacionesPosicion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluacionesPosicion() {
        return $this->evaluacionesPosicion;
    }

    /**
     * Set estabilidadLumboPelvica
     *
     * @param \LogicBundle\Entity\EstabilidadLumboPelvica $estabilidadLumboPelvica
     *
     * @return ConsultaFisioterapia
     */
    public function setEstabilidadLumboPelvica(\LogicBundle\Entity\EstabilidadLumboPelvica $estabilidadLumboPelvica = null) {
        $this->estabilidadLumboPelvica = $estabilidadLumboPelvica;

        return $this;
    }

    /**
     * Get estabilidadLumboPelvica
     *
     * @return \LogicBundle\Entity\EstabilidadLumboPelvica
     */
    public function getEstabilidadLumboPelvica() {
        return $this->estabilidadLumboPelvica;
    }

    /**
     * Set setAndSearch
     *
     * @param \LogicBundle\Entity\SetAndSearch $setAndSearch
     *
     * @return ConsultaFisioterapia
     */
    public function setSetAndSearch(\LogicBundle\Entity\SetAndSearch $setAndSearch = null) {
        $this->setAndSearch = $setAndSearch;

        return $this;
    }

    /**
     * Get setAndSearch
     *
     * @return \LogicBundle\Entity\SetAndSearch
     */
    public function getSetAndSearch() {
        return $this->setAndSearch;
    }

    /**
     * Set sentadillaArranque
     *
     * @param \LogicBundle\Entity\SentadillaArranque $sentadillaArranque
     *
     * @return ConsultaFisioterapia
     */
    public function setSentadillaArranque(\LogicBundle\Entity\SentadillaArranque $sentadillaArranque = null) {
        $this->sentadillaArranque = $sentadillaArranque;

        return $this;
    }

    /**
     * Get sentadillaArranque
     *
     * @return \LogicBundle\Entity\SentadillaArranque
     */
    public function getSentadillaArranque() {
        return $this->sentadillaArranque;
    }

    /**
     * Set postura
     *
     * @param \LogicBundle\Entity\Postura $postura
     *
     * @return ConsultaFisioterapia
     */
    public function setPostura(\LogicBundle\Entity\Postura $postura = null) {
        $this->postura = $postura;

        return $this;
    }

    /**
     * Get postura
     *
     * @return \LogicBundle\Entity\Postura
     */
    public function getPostura() {
        return $this->postura;
    }

    /**
     * Set medidaMMIBalance
     *
     * @param \LogicBundle\Entity\MedidaMMIBalance $medidaMMIBalance
     *
     * @return ConsultaFisioterapia
     */
    public function setMedidaMMIBalance(\LogicBundle\Entity\MedidaMMIBalance $medidaMMIBalance = null) {
        $this->medidaMMIBalance = $medidaMMIBalance;

        return $this;
    }

    /**
     * Get medidaMMIBalance
     *
     * @return \LogicBundle\Entity\MedidaMMIBalance
     */
    public function getMedidaMMIBalance() {
        return $this->medidaMMIBalance;
    }


    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ConsultaFisioterapia
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return ConsultaFisioterapia
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return ConsultaFisioterapia
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Add fichaCampoFisioterapia
     *
     * @param \LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia
     *
     * @return ConsultaFisioterapia
     */
    public function addFichaCampoFisioterapia(\LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia)
    {
        $this->fichaCampoFisioterapias[] = $fichaCampoFisioterapia;

        return $this;
    }

    /**
     * Remove fichaCampoFisioterapia
     *
     * @param \LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia
     */
    public function removeFichaCampoFisioterapia(\LogicBundle\Entity\FichaCampoFisioterapia $fichaCampoFisioterapia)
    {
        $this->fichaCampoFisioterapias->removeElement($fichaCampoFisioterapia);
    }

    /**
     * Get fichaCampoFisioterapias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichaCampoFisioterapias()
    {
        return $this->fichaCampoFisioterapias;
    }

    /**
     * Add testControl
     *
     * @param \LogicBundle\Entity\TestControlFisioterapia $testControl
     *
     * @return ConsultaFisioterapia
     */
    public function addTestControl(\LogicBundle\Entity\TestControlFisioterapia $testControl)
    {
        $this->testControls[] = $testControl;

        return $this;
    }

    /**
     * Remove testControl
     *
     * @param \LogicBundle\Entity\TestControlFisioterapia $testControl
     */
    public function removeTestControl(\LogicBundle\Entity\TestControlFisioterapia $testControl)
    {
        $this->testControls->removeElement($testControl);
    }

    /**
     * Get testControls
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTestControls()
    {
        return $this->testControls;
    }

    /**
     * Add notaFisioterapia
     *
     * @param \LogicBundle\Entity\NotaFisioterapia $notaFisioterapia
     *
     * @return ConsultaFisioterapia
     */
    public function addNotaFisioterapia(\LogicBundle\Entity\NotaFisioterapia $notaFisioterapia)
    {
        $this->notaFisioterapias[] = $notaFisioterapia;

        return $this;
    }

    /**
     * Remove notaFisioterapia
     *
     * @param \LogicBundle\Entity\NotaFisioterapia $notaFisioterapia
     */
    public function removeNotaFisioterapia(\LogicBundle\Entity\NotaFisioterapia $notaFisioterapia)
    {
        $this->notaFisioterapias->removeElement($notaFisioterapia);
    }

    /**
     * Get notaFisioterapias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotaFisioterapias()
    {
        return $this->notaFisioterapias;
    }
}
