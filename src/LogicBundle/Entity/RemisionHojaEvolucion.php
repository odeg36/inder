<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemisionHojaEvolucion
 *
 * @ORM\Table(name="remision_hoja_evolucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RemisionHojaEvolucionRepository")
 */
class RemisionHojaEvolucion
{
    public function __toString() {
        return (string)$this->getHojaEvolucion()->getConsultaMedico()->getDeportista() ? : '';
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
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;
    
    /**
     * @ORM\OneToOne(targetEntity="HojaEvolucion", inversedBy="remisionHojaEvolucion")
     */
    private $hojaEvolucion;

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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return RemisionHojaEvolucion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set hojaEvolucion
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojaEvolucion
     *
     * @return RemisionHojaEvolucion
     */
    public function setHojaEvolucion(\LogicBundle\Entity\HojaEvolucion $hojaEvolucion = null)
    {
        $this->hojaEvolucion = $hojaEvolucion;

        return $this;
    }

    /**
     * Get hojaEvolucion
     *
     * @return \LogicBundle\Entity\HojaEvolucion
     */
    public function getHojaEvolucion()
    {
        return $this->hojaEvolucion;
    }
}
