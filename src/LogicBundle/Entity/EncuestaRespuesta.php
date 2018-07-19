<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EncuestaRespuesta
 *
 * @ORM\Table(name="encuesta_respuesta")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EncuestaRespuestaRepository")
 */
class EncuestaRespuesta
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_respuesta", type="date")
     */
    private $fechaRespuesta;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EncuestaOpcion", inversedBy="encuestaRespuestas")
     * @ORM\JoinColumn(name="encuesta_opcion_id",referencedColumnName="id")
     */
    private $encuestaOpcion;
    
  
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="encuestaRespuestas")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;
    

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
     * Set fechaRespuesta
     *
     * @param \DateTime $fechaRespuesta
     *
     * @return EncuestaRespuesta
     */
    public function setFechaRespuesta($fechaRespuesta)
    {
        $this->fechaRespuesta = $fechaRespuesta;

        return $this;
    }

    /**
     * Get fechaRespuesta
     *
     * @return \DateTime
     */
    public function getFechaRespuesta()
    {
        return $this->fechaRespuesta;
    }
    
    /**
     * Set encuestaOpcion
     *
     * @param \LogicBundle\Entity\EncuestaOpcion $encuestaOpcion
     *
     * @return EncuestaRespuesta
     */
    public function setEncuestaOpcion(\LogicBundle\Entity\EncuestaOpcion $encuestaOpcion = null) {
        $this->encuestaOpcion = $encuestaOpcion;

        return $this;
    }

    /**
     * Get encuestaOpcion
     *
     * @return \LogicBundle\Entity\EncuestaOpcion
     */
    public function getEncuestaOpcion() {
        return $this->encuestaOpcion;
    }

   
     /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return EncuestaRespuesta
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User $usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }
}
