<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FaseEntrenamiento
 *
 * @ORM\Table(name="movimiento_posicion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\MovimientoPosicionRepository")
 */
class MovimientoPosicion {

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
     * @ORM\OneToMany(targetEntity="EvaluacionPosicion", mappedBy="movimientoPosicion")
     */
    private $evaluacionesPosicion;
        /**
     * Constructor
     */
    public function __construct()
    {
        $this->evaluacionesPosicion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return MovimientoPosicion
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
     * Add evaluacionesPosicion
     *
     * @param \LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion
     *
     * @return MovimientoPosicion
     */
    public function addEvaluacionesPosicion(\LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion)
    {
        $this->evaluacionesPosicion[] = $evaluacionesPosicion;

        return $this;
    }

    /**
     * Remove evaluacionesPosicion
     *
     * @param \LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion
     */
    public function removeEvaluacionesPosicion(\LogicBundle\Entity\EvaluacionPosicion $evaluacionesPosicion)
    {
        $this->evaluacionesPosicion->removeElement($evaluacionesPosicion);
    }

    /**
     * Get evaluacionesPosicion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvaluacionesPosicion()
    {
        return $this->evaluacionesPosicion;
    }
}
