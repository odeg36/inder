<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaseEntrenamiento
 *
 * @ORM\Table(name="prueba_lumbo_pelvica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaLumboPelvicaRepository")
 */
class PruebaLumboPelvica {

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
     * @ORM\OneToMany(targetEntity="PruebaEstabilidad", mappedBy="pruebaLumboPelvica")
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $pruebasEstabilidad;
       
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebasEstabilidad = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PruebaLumboPelvica
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
     * Add pruebasEstabilidad
     *
     * @param \LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad
     *
     * @return PruebaLumboPelvica
     */
    public function addPruebasEstabilidad(\LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad)
    {
        $this->pruebasEstabilidad[] = $pruebasEstabilidad;

        return $this;
    }

    /**
     * Remove pruebasEstabilidad
     *
     * @param \LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad
     */
    public function removePruebasEstabilidad(\LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad)
    {
        $this->pruebasEstabilidad->removeElement($pruebasEstabilidad);
    }

    /**
     * Get pruebasEstabilidad
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasEstabilidad()
    {
        return $this->pruebasEstabilidad;
    }
}
