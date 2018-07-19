<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TipoDiagnosticoPsicologia
 *
 * @ORM\Table(name="tipo_diagnostico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoDiagnosticoRepository")
 * @UniqueEntity(
 *     fields={"nombre", "codigo"},
 *     errorPath="nombre"
 * )
 */
class TipoDiagnostico
{
    public function __toString() {
        return $this->getNombre() ? $this->getCodigo() . ' - ' . $this->getNombre() : '';
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=10)
     */
    private $codigo;

    /**
     * @ORM\ManyToMany(targetEntity="DiagnosticoPsicologia", mappedBy="tipoDiagnosticos")
     */
    private $diagnosticoPsicologias;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->diagnosticoPsicologias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoDiagnostico
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
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return TipoDiagnostico
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Add diagnosticoPsicologia.
     *
     * @param \LogicBundle\Entity\DiagnosticoPsicologia $diagnosticoPsicologia
     *
     * @return TipoDiagnostico
     */
    public function addDiagnosticoPsicologia(\LogicBundle\Entity\DiagnosticoPsicologia $diagnosticoPsicologia)
    {
        $this->diagnosticoPsicologias[] = $diagnosticoPsicologia;

        return $this;
    }

    /**
     * Remove diagnosticoPsicologia.
     *
     * @param \LogicBundle\Entity\DiagnosticoPsicologia $diagnosticoPsicologia
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDiagnosticoPsicologia(\LogicBundle\Entity\DiagnosticoPsicologia $diagnosticoPsicologia)
    {
        return $this->diagnosticoPsicologias->removeElement($diagnosticoPsicologia);
    }

    /**
     * Get diagnosticoPsicologias.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnosticoPsicologias()
    {
        return $this->diagnosticoPsicologias;
    }
}
