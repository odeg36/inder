<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NotaFisioterapia
 *
 * @ORM\Table(name="nota_fisioterapia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NotaFisioterapiaRepository")
 */
class NotaFisioterapia {

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
     * @var string
     *
     * @ORM\Column(name="nota_aclaratoria", type="text")
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
     * @ORM\ManyToOne(targetEntity="ConsultaFisioterapia", inversedBy="notaFisioterapias")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $fisioterapia;


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
     * @return NotaFisioterapia
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return NotaFisioterapia
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
     * @return NotaFisioterapia
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
     * Set fisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $fisioterapia
     *
     * @return NotaFisioterapia
     */
    public function setFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $fisioterapia = null)
    {
        $this->fisioterapia = $fisioterapia;

        return $this;
    }

    /**
     * Get fisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getFisioterapia()
    {
        return $this->fisioterapia;
    }
}
