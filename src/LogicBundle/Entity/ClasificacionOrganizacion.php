<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClasificacionOrganizacion
 *
 * @ORM\Table(name="clasificacion_organizacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ClasificacionOrganizacionRepository")
 */
class ClasificacionOrganizacion {

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
     * @ORM\OneToMany(targetEntity="OrganizacionDeportiva", mappedBy="clasificacionOrganizacion")
     */
    private $organizaciones;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organizaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ClasificacionOrganizacion
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
     * Add organizacione
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacione
     *
     * @return ClasificacionOrganizacion
     */
    public function addOrganizacione(\LogicBundle\Entity\OrganizacionDeportiva $organizacione)
    {
        $this->organizaciones[] = $organizacione;

        return $this;
    }

    /**
     * Remove organizacione
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacione
     */
    public function removeOrganizacione(\LogicBundle\Entity\OrganizacionDeportiva $organizacione)
    {
        $this->organizaciones->removeElement($organizacione);
    }

    /**
     * Get organizaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganizaciones()
    {
        return $this->organizaciones;
    }
}
