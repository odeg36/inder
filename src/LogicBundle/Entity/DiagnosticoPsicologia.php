<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosticoPsicologia
 *
 * @ORM\Table(name="diagnostico_psicologia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiagnosticoPsicologiaRepository")
 */
class DiagnosticoPsicologia
{
    const SI = "Si";
    const NO = "No";
    
    const TIPO_ESTUDIO = "Estudio";
    const TIPO_CONFIRMADO = "Confirmado";
    const TIPO_RESUELTO = "Resuelto";

    public function __toString() {
        return (string)$this->getPsicologia()->getDeportista() ? : '';
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=30)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="conducta", type="text", nullable=true)
     */
    private $conducta;

    /**
     * @var bool
     *
     * @ORM\Column(name="desarrolloDesempeno", type="boolean", nullable=true)
     */
    private $desarrolloDesempeno;

    /**
     * @var bool
     *
     * @ORM\Column(name="desarrolloFuncionamiento", type="boolean", nullable=true)
     */
    private $desarrolloFuncionamiento;

    /**
     * @var bool
     *
     * @ORM\Column(name="desarrolloRendimiento", type="boolean", nullable=true)
     */
    private $desarrolloRendimiento;

    /**
     * @var bool
     *
     * @ORM\Column(name="terminacionRendimiento", type="boolean", nullable=true)
     */
    private $terminacionRendimiento;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaPsicologia", inversedBy="diagnostico")
     * @ORM\JoinColumn(name="psicologia_id", referencedColumnName="id")
     */
    private $psicologia;
    
    /**
     * @ORM\ManyToMany(targetEntity="TipoDiagnostico", inversedBy="diagnosticoPsicologias")
     * @ORM\JoinTable(name="diagnostico_tipo_diagnostico_psicologia",
     *      joinColumns={@ORM\JoinColumn(name="diagnostico_psicologia_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tipo_diagnostico_id", referencedColumnName="id")}
     * )
     */
    private $tipoDiagnosticos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tipoDiagnosticos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return DiagnosticoPsicologia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipo.
     *
     * @param string $tipo
     *
     * @return DiagnosticoPsicologia
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo.
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set conducta.
     *
     * @param string|null $conducta
     *
     * @return DiagnosticoPsicologia
     */
    public function setConducta($conducta = null)
    {
        $this->conducta = $conducta;

        return $this;
    }

    /**
     * Get conducta.
     *
     * @return string|null
     */
    public function getConducta()
    {
        return $this->conducta;
    }

    /**
     * Set desarrolloDesempeno.
     *
     * @param bool|null $desarrolloDesempeno
     *
     * @return DiagnosticoPsicologia
     */
    public function setDesarrolloDesempeno($desarrolloDesempeno = null)
    {
        $this->desarrolloDesempeno = $desarrolloDesempeno;

        return $this;
    }

    /**
     * Get desarrolloDesempeno.
     *
     * @return bool|null
     */
    public function getDesarrolloDesempeno()
    {
        return $this->desarrolloDesempeno;
    }

    /**
     * Set desarrolloFuncionamiento.
     *
     * @param bool|null $desarrolloFuncionamiento
     *
     * @return DiagnosticoPsicologia
     */
    public function setDesarrolloFuncionamiento($desarrolloFuncionamiento = null)
    {
        $this->desarrolloFuncionamiento = $desarrolloFuncionamiento;

        return $this;
    }

    /**
     * Get desarrolloFuncionamiento.
     *
     * @return bool|null
     */
    public function getDesarrolloFuncionamiento()
    {
        return $this->desarrolloFuncionamiento;
    }

    /**
     * Set desarrolloRendimiento.
     *
     * @param bool|null $desarrolloRendimiento
     *
     * @return DiagnosticoPsicologia
     */
    public function setDesarrolloRendimiento($desarrolloRendimiento = null)
    {
        $this->desarrolloRendimiento = $desarrolloRendimiento;

        return $this;
    }

    /**
     * Get desarrolloRendimiento.
     *
     * @return bool|null
     */
    public function getDesarrolloRendimiento()
    {
        return $this->desarrolloRendimiento;
    }

    /**
     * Set terminacionRendimiento.
     *
     * @param bool|null $terminacionRendimiento
     *
     * @return DiagnosticoPsicologia
     */
    public function setTerminacionRendimiento($terminacionRendimiento = null)
    {
        $this->terminacionRendimiento = $terminacionRendimiento;

        return $this;
    }

    /**
     * Get terminacionRendimiento.
     *
     * @return bool|null
     */
    public function getTerminacionRendimiento()
    {
        return $this->terminacionRendimiento;
    }

    /**
     * Set psicologia.
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia|null $psicologia
     *
     * @return DiagnosticoPsicologia
     */
    public function setPsicologia(\LogicBundle\Entity\ConsultaPsicologia $psicologia = null)
    {
        $this->psicologia = $psicologia;

        return $this;
    }

    /**
     * Get psicologia.
     *
     * @return \LogicBundle\Entity\ConsultaPsicologia|null
     */
    public function getPsicologia()
    {
        return $this->psicologia;
    }

    /**
     * Add tipoDiagnostico.
     *
     * @param \LogicBundle\Entity\TipoDiagnostico $tipoDiagnostico
     *
     * @return DiagnosticoPsicologia
     */
    public function addTipoDiagnostico(\LogicBundle\Entity\TipoDiagnostico $tipoDiagnostico)
    {
        $this->tipoDiagnosticos[] = $tipoDiagnostico;

        return $this;
    }

    /**
     * Remove tipoDiagnostico.
     *
     * @param \LogicBundle\Entity\TipoDiagnostico $tipoDiagnostico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTipoDiagnostico(\LogicBundle\Entity\TipoDiagnostico $tipoDiagnostico)
    {
        return $this->tipoDiagnosticos->removeElement($tipoDiagnostico);
    }

    /**
     * Get tipoDiagnosticos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoDiagnosticos()
    {
        return $this->tipoDiagnosticos;
    }
}
