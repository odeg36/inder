<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NotaPsicologia
 *
 * @ORM\Table(name="nota_psicologia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NotaPsicologiaRepository")
 */
class NotaPsicologia
{
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
     * @ORM\Column(name="notaAclaratoria", type="text")
     */
    private $notaAclaratoria;

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
     * @ORM\ManyToOne(targetEntity="ConsultaPsicologia", inversedBy="notaPsicologias")
     * @ORM\JoinColumn(name="psicologia_id", referencedColumnName="id")
     */
    private $psicologia;
    
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
     * Set notaAclaratoria
     *
     * @param string $notaAclaratoria
     *
     * @return NotaPsicologia
     */
    public function setNotaAclaratoria($notaAclaratoria)
    {
        $this->notaAclaratoria = $notaAclaratoria;

        return $this;
    }

    /**
     * Get notaAclaratoria
     *
     * @return string
     */
    public function getNotaAclaratoria()
    {
        return $this->notaAclaratoria;
    }

    /**
     * Set psicologia
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia $psicologia
     *
     * @return NotaPsicologia
     */
    public function setPsicologia(\LogicBundle\Entity\ConsultaPsicologia $psicologia = null)
    {
        $this->psicologia = $psicologia;

        return $this;
    }

    /**
     * Get psicologia
     *
     * @return \LogicBundle\Entity\ConsultaPsicologia
     */
    public function getPsicologia()
    {
        return $this->psicologia;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return NotaPsicologia
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
     * @return NotaPsicologia
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
}
