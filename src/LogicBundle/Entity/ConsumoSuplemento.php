<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConsumoSuplemento
 *
 * @ORM\Table(name="consumo_suplemento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ConsumoSuplementoRepository")
 */
class ConsumoSuplemento
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_uso", type="string", length=255)
     */
    private $razonUso;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultaNutricion", inversedBy="consumoSuplementos")
     * @ORM\JoinColumn(name="nutiricion_id", referencedColumnName="id")
     */
    private $nutricion;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="consumoSuplementos")
     * @ORM\JoinColumn(name="medico_id", referencedColumnName="id")
     */
    private $medico;
    
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
     * @return ConsumoSuplemento
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return ConsumoSuplemento
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set razonUso
     *
     * @param string $razonUso
     *
     * @return ConsumoSuplemento
     */
    public function setRazonUso($razonUso)
    {
        $this->razonUso = $razonUso;

        return $this;
    }

    /**
     * Get razonUso
     *
     * @return string
     */
    public function getRazonUso()
    {
        return $this->razonUso;
    }

    /**
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return ConsumoSuplemento
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

    /**
     * Set medico
     *
     * @param \Application\Sonata\UserBundle\Entity\User $medico
     *
     * @return ConsumoSuplemento
     */
    public function setMedico(\Application\Sonata\UserBundle\Entity\User $medico = null)
    {
        $this->medico = $medico;

        return $this;
    }

    /**
     * Get medico
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getMedico()
    {
        return $this->medico;
    }
}
