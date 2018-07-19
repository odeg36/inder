<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnidadDeportiva
 *
 * @ORM\Table(name="unidad_deportiva")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\UnidadDeportivaRepository")
 */
class UnidadDeportiva
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
     * @var boolean
     *
     * @ORM\Column(name="habilitado", type="boolean")
     */
    private $habilitado;
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EscenarioDeportivo", mappedBy="unidadDeportiva")
     */
    private $escenariosDeportivo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->escenariosDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return UnidadDeportiva
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
     * Set habilitado
     *
     * @param boolean $habilitado
     *
     * @return UnidadDeportiva
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return boolean
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Remove escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     */
    public function removeEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo)
    {
        $this->escenariosDeportivo->removeElement($escenarioDeportivo);
    }

    /**
     * Get escenarioDeportivos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenarioDeportivo()
    {
        return $this->escenariosDeportivo;
    }

    /**
     * Add escenariosDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenariosDeportivo
     *
     * @return UnidadDeportiva
     */
    public function addEscenariosDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenariosDeportivo)
    {
        $this->escenariosDeportivo[] = $escenariosDeportivo;

        return $this;
    }

    /**
     * Remove escenariosDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenariosDeportivo
     */
    public function removeEscenariosDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenariosDeportivo)
    {
        $this->escenariosDeportivo->removeElement($escenariosDeportivo);
    }

    /**
     * Get escenariosDeportivo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscenariosDeportivo()
    {
        return $this->escenariosDeportivo;
    }
}
