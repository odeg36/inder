<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Remision
 *
 * @ORM\Table(name="remision")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RemisionRepository")
 */
class Remision
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
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaPsicologia", inversedBy="remision")
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Remision
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
     * Set psicologia
     *
     * @param \LogicBundle\Entity\ConsultaPsicologia $psicologia
     *
     * @return Remision
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
}
