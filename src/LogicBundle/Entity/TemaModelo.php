<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TemaModelo
 *
 * @ORM\Table(name="tema_modelo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TemaModeloRepository")
 */
class TemaModelo
{
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
     * @ORM\Column(name="nombre", type="string", length=191)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=191)
     */
    private $imagen;


    ///////relacion con modelo
    /**
     * @ORM\ManyToOne(targetEntity="Modelo");
     * @ORM\JoinColumn(name="modelo_id", referencedColumnName="id")
     */
    private $modelo;

    ////////// relacion con temaPorComuna
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TemaPorComuna", mappedBy="temaModelo");
     */
    private $temaPorComuna;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->temaPorComuna = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TemaModelo
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
     * Set imagen
     *
     * @param string $imagen
     *
     * @return TemaModelo
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set modelo
     *
     * @param \LogicBundle\Entity\Modelo $modelo
     *
     * @return TemaModelo
     */
    public function setModelo(\LogicBundle\Entity\Modelo $modelo = null)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return \LogicBundle\Entity\Modelo
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Add temaPorComuna
     *
     * @param \LogicBundle\Entity\TemaPorComuna $temaPorComuna
     *
     * @return TemaModelo
     */
    public function addTemaPorComuna(\LogicBundle\Entity\TemaPorComuna $temaPorComuna)
    {
        $this->temaPorComuna[] = $temaPorComuna;

        return $this;
    }

    /**
     * Remove temaPorComuna
     *
     * @param \LogicBundle\Entity\TemaPorComuna $temaPorComuna
     */
    public function removeTemaPorComuna(\LogicBundle\Entity\TemaPorComuna $temaPorComuna)
    {
        $this->temaPorComuna->removeElement($temaPorComuna);
    }

    /**
     * Get temaPorComuna
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTemaPorComuna()
    {
        return $this->temaPorComuna;
    }
}
