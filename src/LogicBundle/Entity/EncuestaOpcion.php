<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuestaOpcion
 *
 * @ORM\Table(name="encuesta_opcion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuestaOpcionRepository")
 */
class EncuestaOpcion
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuestaPregunta", inversedBy="encuestaOpciones",cascade={ "persist"})
     * @ORM\JoinColumn(name="encuesta_pregrunta_id",referencedColumnName="id")
     */
    private $encuestaPregunta;    
    
    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\EncuestaRespuesta", mappedBy="encuestaOpcion")
     */
    private $encuestaRespuestas;
   
    /**
     * Constructor
     **/
     public function __construct() {
       $this->encuestaRespuestas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EncuestaOpcion
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
     * Add encuestaRespuesta
     *
     * @param \LogicBundle\Entity\EncuestaRespuesta $encuestaRespuesta
     *
     * @return EncuestaOpcion
     */
    public function addEncuestaRespuesta(\LogicBundle\Entity\EncuestaRespuesta $encuestaRespuesta) {
        $encuestaRespuesta->setEncuestaOpcion($this);
        $this->encuestaRespuestas[] = $encuestaRespuesta;

        return $this;
    }

    /**
     * Remove encuestaRespuesta
     *
     * @param \LogicBundle\Entity\EncuestaRespuesta $encuestaRespuesta
     */
    public function removeEncuestaRespuesta(\LogicBundle\Entity\EncuestaRespuesta $encuestaRespuesta) {
        $this->encuestaRespuestas->removeElement($encuestaRespuesta);
    }

    /**
     * Get encuestaRespuestas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncuestaRespuestas() {
        return $this->encuestaRespuestas;
    }

    /**
     * Set encuestaPregunta
     *
     * @param \LogicBundle\Entity\EncuestaPregunta $encuestaPregunta
     *
     * @return EncuestaPregunta
     */
    public function setEncuestaPregunta(\LogicBundle\Entity\EncuestaPregunta $encuestaPregunta = null) {
        $this->encuestaPregunta = $encuestaPregunta;

        return $this;
    }

    /**
     * Get EncuestaPregunta
     *
     * @return \LogicBundle\Entity\EncuestaPregunta
     */
    public function getEncuestaPregunta() {
        return $this->encuestaPregunta;
    }
}
