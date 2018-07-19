<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Medicamento
 *
 * @ORM\Table(name="medicamento")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MedicamentoRepository")
 */
class Medicamento
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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Tratamiento", mappedBy="medicamento")
     */
    private $tratamientos;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\TratamientoHojaEvolucion", mappedBy="medicamento")
     */
    private $tratamientosHojasEvoluciones;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tratamientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tratamientosHojasEvoluciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Medicamento
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
     * Add tratamiento
     *
     * @param \LogicBundle\Entity\Tratamiento $tratamiento
     *
     * @return Medicamento
     */
    public function addTratamiento(\LogicBundle\Entity\Tratamiento $tratamiento)
    {
        $this->tratamientos[] = $tratamiento;

        return $this;
    }

    /**
     * Remove tratamiento
     *
     * @param \LogicBundle\Entity\Tratamiento $tratamiento
     */
    public function removeTratamiento(\LogicBundle\Entity\Tratamiento $tratamiento)
    {
        $this->tratamientos->removeElement($tratamiento);
    }

    /**
     * Get tratamientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTratamientos()
    {
        return $this->tratamientos;
    }

    /**
     * Add tratamientosHojasEvolucione
     *
     * @param \LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione
     *
     * @return Medicamento
     */
    public function addTratamientosHojasEvolucione(\LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione)
    {
        $this->tratamientosHojasEvoluciones[] = $tratamientosHojasEvolucione;

        return $this;
}

    /**
     * Remove tratamientosHojasEvolucione
     *
     * @param \LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione
     */
    public function removeTratamientosHojasEvolucione(\LogicBundle\Entity\TratamientoHojaEvolucion $tratamientosHojasEvolucione)
    {
        $this->tratamientosHojasEvoluciones->removeElement($tratamientosHojasEvolucione);
    }

    /**
     * Get tratamientosHojasEvoluciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTratamientosHojasEvoluciones()
    {
        return $this->tratamientosHojasEvoluciones;
    }
}
