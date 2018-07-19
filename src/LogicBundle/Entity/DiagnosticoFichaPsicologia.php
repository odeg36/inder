<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosticoFichaPsicologia
 *
 * @ORM\Table(name="diagnostico_ficha_psicologia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DiagnosticoFichaPsicologiaRepository")
 */
class DiagnosticoFichaPsicologia
{
    public function __toString() {
        return (string)$this->getPsicologia()->getDeportista() ? : '';
    }
    
    const TIPO_ESTUDIO = "Estudio";
    const TIPO_CONFIRMADO = "Confirmado";
    const TIPO_RESUELTO = "Resuelto";
    
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
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=30)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="conducta", type="text")
     */
    private $conducta;

    /**
     * @ORM\ManyToOne(targetEntity="FichaCampoPsicologia", inversedBy="diagnosticos")
     * @ORM\JoinColumn(name="ficha_campo_psicologia_id", referencedColumnName="id")
     */
    private $fichaCampoPsicologia;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return DiagnosticoFichaPsicologia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return DiagnosticoFichaPsicologia
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set conducta
     *
     * @param string $conducta
     *
     * @return DiagnosticoFichaPsicologia
     */
    public function setConducta($conducta)
    {
        $this->conducta = $conducta;

        return $this;
    }

    /**
     * Get conducta
     *
     * @return string
     */
    public function getConducta()
    {
        return $this->conducta;
    }

    /**
     * Set fichaCampoPsicologia
     *
     * @param \LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia
     *
     * @return DiagnosticoFichaPsicologia
     */
    public function setFichaCampoPsicologia(\LogicBundle\Entity\FichaCampoPsicologia $fichaCampoPsicologia = null)
    {
        $this->fichaCampoPsicologia = $fichaCampoPsicologia;

        return $this;
    }

    /**
     * Get fichaCampoPsicologia
     *
     * @return \LogicBundle\Entity\FichaCampoPsicologia
     */
    public function getFichaCampoPsicologia()
    {
        return $this->fichaCampoPsicologia;
    }
}
