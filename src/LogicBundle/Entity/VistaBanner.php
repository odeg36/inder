<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VistaBanner
 *
 * @ORM\Table(name="vista_banner")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\VistaBannerRepository")
 */
class VistaBanner
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Banner")
     * @ORM\JoinColumn(name="banner_id", referencedColumnName="id", nullable=true)
     */
     private $banner;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
     protected $usuario;
     

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="dispositivo", type="string", length=255)
     */
    private $dispositivo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return VistaBanner
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set dispositivo
     *
     * @param string $dispositivo
     *
     * @return VistaBanner
     */
    public function setDispositivo($dispositivo)
    {
        $this->dispositivo = $dispositivo;

        return $this;
    }

    /**
     * Get dispositivo
     *
     * @return string
     */
    public function getDispositivo()
    {
        return $this->dispositivo;
    }

    /**
     * Set banner
     *
     * @param \LogicBundle\Entity\Banner $banner
     *
     * @return Banner
     */
    public function setBanner(\LogicBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return \LogicBundle\Entity\Banner
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return User
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

}
