<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactorExterno
 *
 * @ORM\Table(name="factor_externo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\FactorExternoRepository")
 */
class FactorExterno
{
    
    public function __toString() {
        return $this->getNombre()? : '';
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
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="ValoracionAlimentacionFactorExterno", mappedBy="factorExterno")
     */
    private $factorExternos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factorExternos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return FactorExterno
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
     * Add factorExterno
     *
     * @param \LogicBundle\Entity\ValoracionAlimentacionFactorExterno $factorExterno
     *
     * @return FactorExterno
     */
    public function addFactorExterno(\LogicBundle\Entity\ValoracionAlimentacionFactorExterno $factorExterno)
    {
        $this->factorExternos[] = $factorExterno;

        return $this;
    }

    /**
     * Remove factorExterno
     *
     * @param \LogicBundle\Entity\ValoracionAlimentacionFactorExterno $factorExterno
     */
    public function removeFactorExterno(\LogicBundle\Entity\ValoracionAlimentacionFactorExterno $factorExterno)
    {
        $this->factorExternos->removeElement($factorExterno);
    }

    /**
     * Get factorExternos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactorExternos()
    {
        return $this->factorExternos;
    }
}
