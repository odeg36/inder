<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuestaPregunta
 *
 * @ORM\Table(name="encuesta_pregunta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuestaPreguntaRepository")
 */
class EncuestaPregunta
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuestaOpcion", mappedBy="encuestaPregunta",cascade={"persist"}, orphanRemoval=true)
     */
    private $encuestaOpciones;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Encuesta", inversedBy="encuestaPreguntas",cascade={ "persist"})
     * @ORM\JoinColumn(name="encuesta_id",referencedColumnName="id")
     */
    private $encuesta;

    /**
     * Constructor
     */
    public function __construct() {
        $this->encuestaOpciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EncuestaPregunta
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
     * Add encuestaOpcion
     *
     * @param \LogicBundle\Entity\EncuestaOpcion $encuestaOpcion
     *
     * @return EncuestaPregunta
     */
    public function addEncuestaOpcion(\LogicBundle\Entity\EncuestaOpcion $encuestaOpcion) {
        $encuestaOpcion->setEncuestaPregunta($this);
        $this->encuestaOpciones[] = $encuestaOpcion;

        return $this;
    }

    /**
     * Remove encuestaOpcion
     *
     * @param \LogicBundle\Entity\EncuestaOpcion $encuestaOpcion
     */
    public function removeEncuestaOpcion(\LogicBundle\Entity\EncuestaOpcion $encuestaOpcion) {
        $this->encuestaOpciones->removeElement($encuestaOpcion);
    }

    /**
     * Get encuestaOpciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestaOpciones() {
        return $this->encuestaOpciones;
    }

    /**
     * Set encuesta
     *
     * @param \LogicBundle\Entity\Encuesta $encuesta
     *
     * @return EncuestaPregunta
     */
    public function setEncuesta(\LogicBundle\Entity\Encuesta $encuesta = null) {
        $this->encuesta = $encuesta;

        return $this;
    }

    /**
     * Get Encuesta
     *
     * @return \LogicBundle\Entity\Encuesta
     */
    public function getEncuesta() {
        return $this->encuesta;
    }

    /**
     * Add encuestaOpcione
     *
     * @param \LogicBundle\Entity\EncuestaOpcion $encuestaOpcione
     *
     * @return EncuestaPregunta
     */
    public function addEncuestaOpcione(\LogicBundle\Entity\EncuestaOpcion $encuestaOpcione)
    {
        $this->encuestaOpciones[] = $encuestaOpcione;

        return $this;
    }

    /**
     * Remove encuestaOpcione
     *
     * @param \LogicBundle\Entity\EncuestaOpcion $encuestaOpcione
     */
    public function removeEncuestaOpcione(\LogicBundle\Entity\EncuestaOpcion $encuestaOpcione)
    {
        $this->encuestaOpciones->removeElement($encuestaOpcione);
    }
}
