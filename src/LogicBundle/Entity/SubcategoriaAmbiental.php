<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LogicBundle\Entity\CampoAmbiental;


/**
 * SubcategoriaAmbiental
 *
 * @ORM\Table(name="subcategoria_ambiental")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SubcategoriaAmbientalRepository")
 */
class SubcategoriaAmbiental
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaAmbiental",cascade={ "persist"}, inversedBy="subcategoriaAmbientales")
     * @ORM\JoinColumn(name="categoria_ambiental_id", referencedColumnName="id", )
     */
    private $categoriaAmbiental;
    
    /**
     * Many subcategorias have Many campoInfraestructuras.
     * @ORM\ManyToMany(targetEntity="CampoAmbiental",cascade={ "persist"}, inversedBy="subAmbientalCampos")
     * @ORM\JoinTable(name="subcategoria_ambiental_campos")
     */
    private $campoAmbientales;
    
    /**
     * @ORM\OneToMany(targetEntity="EscenarioSubCategoriaAmbiental", mappedBy="subcategoriaAmbiental")
     */
    private $escenarioSubcategoriaAmbientales;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campoAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escenarioSubcategoriaAmbientales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return SubcategoriaAmbiental
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
     * Set categoriaAmbiental
     *
     * @param \LogicBundle\Entity\CategoriaAmbiental $categoriaAmbiental
     *
     * @return SubcategoriaAmbiental
     */
    public function setCategoriaAmbiental(\LogicBundle\Entity\CategoriaAmbiental $categoriaAmbiental = null)
    {
        $this->categoriaAmbiental = $categoriaAmbiental;

        return $this;
    }

    /**
     * Get categoriaAmbiental
     *
     * @return \LogicBundle\Entity\CategoriaAmbiental
     */
    public function getCategoriaAmbiental()
    {
        return $this->categoriaAmbiental;
    }

    /**
     * Add campoAmbientale
     *
     * @param \LogicBundle\Entity\CampoAmbiental $campoAmbientale
     *
     * @return SubcategoriaAmbiental
     */
    public function addCampoAmbientale(\LogicBundle\Entity\CampoAmbiental $campoAmbientale)
    {
        $this->campoAmbientales[] = $campoAmbientale;

        return $this;
    }

    /**
     * Remove campoAmbientale
     *
     * @param \LogicBundle\Entity\CampoAmbiental $campoAmbientale
     */
    public function removeCampoAmbientale(\LogicBundle\Entity\CampoAmbiental $campoAmbientale)
    {
        $this->campoAmbientales->removeElement($campoAmbientale);
    }

    /**
     * Get campoAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoAmbientales()
    {
        return $this->campoAmbientales;
    }

    /**
     * Add escenarioSubcategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubcategoriaAmbientale
     *
     * @return SubcategoriaAmbiental
     */
    public function addEscenarioSubcategoriaAmbientale(\LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubcategoriaAmbientale)
    {
        $this->escenarioSubcategoriaAmbientales[] = $escenarioSubcategoriaAmbientale;

        return $this;
    }

    /**
     * Remove escenarioSubcategoriaAmbientale
     *
     * @param \LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubcategoriaAmbientale
     */
    public function removeEscenarioSubcategoriaAmbientale(\LogicBundle\Entity\EscenarioSubCategoriaAmbiental $escenarioSubcategoriaAmbientale)
    {
        $this->escenarioSubcategoriaAmbientales->removeElement($escenarioSubcategoriaAmbientale);
    }

    /**
     * Get escenarioSubcategoriaAmbientales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioSubcategoriaAmbientales()
    {
        return $this->escenarioSubcategoriaAmbientales;
    }
}
