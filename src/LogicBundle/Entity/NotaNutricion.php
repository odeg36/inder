<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NotaNutricion
 *
 * @ORM\Table(name="nota_nutricion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NotaNutricionRepository")
 */
class NotaNutricion
{
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
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
     * @ORM\ManyToOne(targetEntity="ConsultaNutricion", inversedBy="notaNutriciones")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;


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
     * @return NotaNutricion
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
     * @return NotaNutricion
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
     * @return NotaNutricion
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
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return NotaNutricion
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }
}
