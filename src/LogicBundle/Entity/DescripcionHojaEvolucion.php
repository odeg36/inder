<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescripcionHojaEvolucion
 *
 * @ORM\Table(name="descripcion_hoja_evolucion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DescripcionHojaEvolucionRepository")
 */
class DescripcionHojaEvolucion {

    public function __toString() {
        return (string) $this->getHojaEvolucion()->getConsultaMedico()->getDeportista() ?: '';
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
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @ORM\OneToOne(targetEntity="HojaEvolucion", inversedBy="descripcionHojaEvolucion")
     */
    private $hojaEvolucion;

    /**
     * @ORM\OneToMany(targetEntity="Diagnostico", mappedBy="descripcionHojaEvolucion", cascade={"persist"})
     */
    private $diagnosticos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->diagnosticos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return DescripcionHojaEvolucion
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set hojaEvolucion
     *
     * @param \LogicBundle\Entity\HojaEvolucion $hojaEvolucion
     *
     * @return DescripcionHojaEvolucion
     */
    public function setHojaEvolucion(\LogicBundle\Entity\HojaEvolucion $hojaEvolucion = null) {
        $this->hojaEvolucion = $hojaEvolucion;

        return $this;
    }

    /**
     * Get hojaEvolucion
     *
     * @return \LogicBundle\Entity\HojaEvolucion
     */
    public function getHojaEvolucion() {
        return $this->hojaEvolucion;
    }

    /**
     * Add diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     *
     * @return DescripcionHojaEvolucion
     */
    public function addDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico) {
        $diagnostico->setDescripcionHojaEvolucion($this);
        $this->diagnosticos[] = $diagnostico;

        return $this;
    }

    /**
     * Remove diagnostico
     *
     * @param \LogicBundle\Entity\Diagnostico $diagnostico
     */
    public function removeDiagnostico(\LogicBundle\Entity\Diagnostico $diagnostico) {
        $this->diagnosticos->removeElement($diagnostico);
    }

    /**
     * Get diagnosticos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnosticos() {
        return $this->diagnosticos;
    }

}
