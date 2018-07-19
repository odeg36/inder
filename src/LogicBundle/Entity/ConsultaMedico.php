<?php

namespace LogicBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * ConsultaMedica
 *
 * @ORM\Table(name="consulta_medica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ConsultaMedicoRepository")
 */
class ConsultaMedico {

    public function __toString() {
        if ($this->getFechaCreacion()) {
            return $this->getFechaCreacion()->format("d/m/Y") . ' - ' . $this->getDeportista();
        }

        return (string) $this->getId();
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="ConsultasMedicas")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;

    /**
     * @ORM\OneToOne(targetEntity="ValoracionInicial", mappedBy="consultaMedico")
     */
    private $valoracionInicial;

    /**
     * @ORM\OneToOne(targetEntity="AntecedenteMedico", mappedBy="consultaMedico")
     */
    private $antecedenteMedico;

    /**
     * @ORM\OneToOne(targetEntity="ExamenFisicoMedico", mappedBy="consultaMedico")
     */
    private $examenFisicoMedico;

    /**
     * @ORM\OneToMany(targetEntity="RegistroParaclinico", mappedBy="consultaMedico")
     */
    private $registrosParaclinicos;

    /**
     * @ORM\OneToMany(targetEntity="Tratamiento", mappedBy="consultaMedico")
     */
    private $tratamientos;

    /**
     * @ORM\OneToMany(targetEntity="LaboratorioMedico", mappedBy="consultaMedico")
     */
    private $laboratoriosMedicos;

    /**
     * @ORM\OneToOne(targetEntity="RemisionMedico", mappedBy="consultaMedico")
     */
    private $remision;

    /**
     * @ORM\OneToOne(targetEntity="ContrareferenciaRecomendacion", mappedBy="consultaMedico")
     */
    private $contrareferenciaRecomendacion;

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
     * @ORM\OneToMany(targetEntity="HojaEvolucion", mappedBy="consultaMedico")
     */
    private $hojasEvoluciones;

    /**
     * @ORM\OneToMany(targetEntity="NotaAclaratoria", mappedBy="consultaMedico")
     */
    private $notasAclaratorias;

    /**
     * Constructor
     */
    public function __construct() {
        $this->registrosParaclinicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tratamientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laboratoriosMedicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hojasEvoluciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notasAclaratorias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ConsultaMedico
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
     * @return ConsultaMedico
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
     * Set finalizado
     *
     * @param boolean $finalizado
     *
     * @return ConsultaMedico
     */
    public function setFinalizado($finalizado) {
        $this->finalizado = $finalizado;

        return $this;
    }

    /**
     * Get finalizado
     *
     * @return boolean
     */
    public function getFinalizado() {
        return $this->finalizado;
    }

    /**
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ConsultaMedico
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
     * Set valoracionInicial
     *
     * @param \LogicBundle\Entity\ValoracionInicial $valoracionInicial
     *
     * @return ConsultaMedico
     */
    public function setValoracionInicial(\LogicBundle\Entity\ValoracionInicial $valoracionInicial = null) {
        $this->valoracionInicial = $valoracionInicial;

        return $this;
    }

    /**
     * Get valoracionInicial
     *
     * @return \LogicBundle\Entity\ValoracionInicial
     */
    public function getValoracionInicial() {
        return $this->valoracionInicial;
    }

    /**
     * Set antecedenteMedico
     *
     * @param \LogicBundle\Entity\AntecedenteMedico $antecedenteMedico
     *
     * @return ConsultaMedico
     */
    public function setAntecedenteMedico(\LogicBundle\Entity\AntecedenteMedico $antecedenteMedico = null) {
        $this->antecedenteMedico = $antecedenteMedico;

        return $this;
    }

    /**
     * Get antecedenteMedico
     *
     * @return \LogicBundle\Entity\AntecedenteMedico
     */
    public function getAntecedenteMedico() {
        return $this->antecedenteMedico;
    }

    /**
     * Set examenFisicoMedico
     *
     * @param \LogicBundle\Entity\ExamenFisicoMedico $examenFisicoMedico
     *
     * @return ConsultaMedico
     */
    public function setExamenFisicoMedico(\LogicBundle\Entity\ExamenFisicoMedico $examenFisicoMedico = null) {
        $this->examenFisicoMedico = $examenFisicoMedico;

        return $this;
    }

    /**
     * Get examenFisicoMedico
     *
     * @return \LogicBundle\Entity\ExamenFisicoMedico
     */
    public function getExamenFisicoMedico() {
        return $this->examenFisicoMedico;
    }

    /**
     * Add registrosParaclinico
     *
     * @param \LogicBundle\Entity\RegistroParaclinico $registrosParaclinico
     *
     * @return ConsultaMedico
     */
    public function addRegistrosParaclinico(\LogicBundle\Entity\RegistroParaclinico $registrosParaclinico) {
        $this->registrosParaclinicos[] = $registrosParaclinico;

        return $this;
    }

    /**
     * Remove registrosParaclinico
     *
     * @param \LogicBundle\Entity\RegistroParaclinico $registrosParaclinico
     */
    public function removeRegistrosParaclinico(\LogicBundle\Entity\RegistroParaclinico $registrosParaclinico) {
        $this->registrosParaclinicos->removeElement($registrosParaclinico);
    }

    /**
     * Get registrosParaclinicos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistrosParaclinicos() {
        return $this->registrosParaclinicos;
    }

    /**
     * Add tratamiento
     *
     * @param \LogicBundle\Entity\Tratamiento $tratamiento
     *
     * @return ConsultaMedico
     */
    public function addTratamiento(\LogicBundle\Entity\Tratamiento $tratamiento) {
        $this->tratamientos[] = $tratamiento;

        return $this;
    }

    /**
     * Remove tratamiento
     *
     * @param \LogicBundle\Entity\Tratamiento $tratamiento
     */
    public function removeTratamiento(\LogicBundle\Entity\Tratamiento $tratamiento) {
        $this->tratamientos->removeElement($tratamiento);
    }

    /**
     * Get tratamientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTratamientos() {
        return $this->tratamientos;
    }

    /**
     * Add laboratoriosMedico
     *
     * @param \LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico
     *
     * @return ConsultaMedico
     */
    public function addLaboratoriosMedico(\LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico) {
        $this->laboratoriosMedicos[] = $laboratoriosMedico;

        return $this;
    }

    /**
     * Remove laboratoriosMedico
     *
     * @param \LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico
     */
    public function removeLaboratoriosMedico(\LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico) {
        $this->laboratoriosMedicos->removeElement($laboratoriosMedico);
    }

    /**
     * Get laboratoriosMedicos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratoriosMedicos() {
        return $this->laboratoriosMedicos;
    }

    /**
     * Set remision
     *
     * @param \LogicBundle\Entity\RemisionMedico $remision
     *
     * @return ConsultaMedico
     */
    public function setRemision(\LogicBundle\Entity\RemisionMedico $remision = null) {
        $this->remision = $remision;

        return $this;
    }

    /**
     * Get remision
     *
     * @return \LogicBundle\Entity\RemisionMedico
     */
    public function getRemision() {
        return $this->remision;
    }

    /**
     * Set contrareferenciaRecomendacion
     *
     * @param \LogicBundle\Entity\ContrareferenciaRecomendacion $contrareferenciaRecomendacion
     *
     * @return ConsultaMedico
     */
    public function setContrareferenciaRecomendacion(\LogicBundle\Entity\ContrareferenciaRecomendacion $contrareferenciaRecomendacion = null) {
        $this->contrareferenciaRecomendacion = $contrareferenciaRecomendacion;

        return $this;
    }

    /**
     * Get contrareferenciaRecomendacion
     *
     * @return \LogicBundle\Entity\ContrareferenciaRecomendacion
     */
    public function getContrareferenciaRecomendacion() {
        return $this->contrareferenciaRecomendacion;
    }

    /**
     * Add hojasEvolucione
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojasEvolucione
     *
     * @return ConsultaMedico
     */
    public function addHojasEvolucione(\LogicBundle\Entity\HojaEvolucion $hojasEvolucione) {
        $this->hojasEvoluciones[] = $hojasEvolucione;

        return $this;
    }

    /**
     * Remove hojasEvolucione
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojasEvolucione
     */
    public function removeHojasEvolucione(\LogicBundle\Entity\HojaEvolucion $hojasEvolucione) {
        $this->hojasEvoluciones->removeElement($hojasEvolucione);
    }

    /**
     * Get hojasEvoluciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHojasEvoluciones() {
        return $this->hojasEvoluciones;
    }

    /**
     * Add notasAclaratoria
     *
     * @param \LogicBundle\Entity\NotaAclaratoria $notasAclaratoria
     *
     * @return ConsultaMedico
     */
    public function addNotasAclaratoria(\LogicBundle\Entity\NotaAclaratoria $notasAclaratoria) {
        $this->notasAclaratorias[] = $notasAclaratoria;

        return $this;
    }

    /**
     * Remove notasAclaratoria
     *
     * @param \LogicBundle\Entity\NotaAclaratoria $notasAclaratoria
     */
    public function removeNotasAclaratoria(\LogicBundle\Entity\NotaAclaratoria $notasAclaratoria) {
        $this->notasAclaratorias->removeElement($notasAclaratoria);
    }

    /**
     * Get notasAclaratorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasAclaratorias() {
        return $this->notasAclaratorias;
    }

}
