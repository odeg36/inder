<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enlace
 *
 * @ORM\Table(name="enlace")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EnlaceRepository")
 */
class Enlace
{

    public function __toString() {
        return $this->titulo ? $this->titulo : '';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\CategoriaEnlace", inversedBy="enlaces")
     * @ORM\JoinColumn(name="categoria_enlace", referencedColumnName="id")     
     */
    private $categoriaEnlace;    

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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Enlace
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Enlace
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set categoriaEnlace
     *
     * @param \LogicBundle\Entity\CategoriaEnlace $categoriaEnlace
     *
     * @return Enlace
     */
    public function setCategoriaEnlace(\LogicBundle\Entity\CategoriaEnlace $categoriaEnlace = null) {
        $this->categoriaEnlace = $categoriaEnlace;

        return $this;
    }

    /**
     * Get categoriaEnlace
     *
     * @return \LogicBundle\Entity\CategoriaEnlace
     */
    public function getCategoriaEnlace() {
        return $this->categoriaEnlace;
    }
}
