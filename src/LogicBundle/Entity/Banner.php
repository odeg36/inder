<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\Comuna as Comuna;

/**
 * Banner
 *
 * @ORM\Table(name="banner")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\BannerRepository")
 */
class Banner
{

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * Many Banner have Many Comuna.
     * @ORM\ManyToMany(targetEntity="Comuna", inversedBy="banners")
     * @ORM\JoinTable(name="comuna_banner",
     *      joinColumns={@ORM\JoinColumn(name="banner_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="comuna_id", referencedColumnName="id")}
     *      )
     */
    private $comunas;
     
     
    public function __construct() {
        $this->comunas = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date")
     */
     private $fechaInicio;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre",  type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="date")
     */
    private $fechaFin;

    /**
     * @var int
     *
     * @ORM\Column(name="veces_visto", type="integer")
     */
    private $vecesVisto;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen_web", type="string", length=255)
     */
    private $imagenWeb;

    /**
     * Get imagenWeb
     *
     * @return string
     */
     public function getImagenWeb() {
        if (!$this->imagenWeb) {
            $this->imagenWeb = "img-perfil.png";
        }
        return $this->imagenWeb;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
        }

        $filename = $file->getClientOriginalName();

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = uniqid(date('YmdHis')) . '.' . $ext;
        $file->move(
                $path, $filename
        );

        return $filename;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="imagen_mobil", type="string", length=255)
     */
    private $imagenMobil;

    /**
     * Get imagenMobil
     *
     * @return string
     */
     public function getImagenMobil() {
        if (!$this->imagenMobil) {
            $this->imagenMobil = "img-perfil.png";
        }
        return $this->imagenMobil;
    }

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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Banner
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Banner
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set vecesVisto
     *
     * @param integer $vecesVisto
     *
     * @return Banner
     */
    public function setVecesVisto($vecesVisto)
    {
        $this->vecesVisto = $vecesVisto;

        return $this;
    }

    /**
     * Get vecesVisto
     *
     * @return int
     */
    public function getVecesVisto()
    {
        return $this->vecesVisto;
    }

    /**
     * Set imagenWeb
     *
     * @param string $imagenWeb
     *
     * @return Banner
     */
    public function setImagenWeb($imagenWeb)
    {
        $this->imagenWeb = $imagenWeb;

        return $this;
    }

    
    /**
     * Set imagenMobil
     *
     * @param string $imagenMobil
     *
     * @return Banner
     */
    public function setImagenMobil($imagenMobil)
    {
        $this->imagenMobil = $imagenMobil;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string $nombre
     */
     public function getNombre()
     {
         return $this->nombre;
     }
 
     /**
      * Set nombre
      *
      * @param string $nombre
      *
      * @return Banner
      */
     public function setNombre($nombre)
     {
         $this->nombre = $nombre;
 
         return $this;
     }

    /**
    * Get comunas
    *
    * @return \Doctrine\Common\Collections\Collection 
    */
    public function getComunas()
    {
        return $this->comunas;
    }
    
    /**
     * Add comuna
     *
     * @param \Acme\AppBundle\Entity\Comuna $comuna
     * @return PurchaseOrder
     */
    public function addComunas(Comuna $comuna)
    {
        $this->comunas->add($comuna);
    }
    


    /**
     * Add comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     *
     * @return Banner
     */
    public function addComuna(\LogicBundle\Entity\Comuna $comuna)
    {
        $this->comunas[] = $comuna;

        return $this;
    }

    /**
     * Remove comuna
     *
     * @param \LogicBundle\Entity\Comuna $comuna
     */
    public function removeComuna(\LogicBundle\Entity\Comuna $comuna)
    {
        $this->comunas->removeElement($comuna);
    }
}
