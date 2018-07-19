<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PruebaPostura
 *
 * @ORM\Table(name="prueba_postura")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaPosturaRepository")
 */
class PruebaPostura {

    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToOne(targetEntity="Postura", inversedBy="pruebaPosturas")
     * @ORM\JoinColumn(name="postura_id", referencedColumnName="id")
     */
    private $postura;
    //// ***************    FIN MODIFICACIONES     ************* /////


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
     * Set numero
     *
     * @param integer $numero
     *
     * @return PruebaPostura
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return PruebaPostura
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set postura
     *
     * @param Postura $postura
     *
     * @return PruebaPostura
     */
    public function setPostura(Postura $postura = null)
    {
        $this->postura = $postura;

        return $this;
    }

    /**
     * Get postura
     *
     * @return Postura
     */
    public function getPostura()
    {
        return $this->postura;
    }
}
