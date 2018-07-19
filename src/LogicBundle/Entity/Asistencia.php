<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Asistencia
 *
 * @ORM\Table(name="asistencia")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AsistenciaRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Asistencia
{
    const SI = "Si";
    const NO = "No";
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     * @Serializer\Expose
     */
    private $fecha;

    /**
     * @var bool
     *
     * @ORM\Column(name="asistio", type="boolean", nullable=true)
     * @Serializer\Expose
     */
    private $asistio = false;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="asistencias")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * @Serializer\Expose
     */
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="Oferta", inversedBy="asistencias")
     * @ORM\JoinColumn(name="oferta_id", referencedColumnName="id")
     */
    private $oferta;
    
    /**
     * @ORM\ManyToOne(targetEntity="Programacion", inversedBy="asistencias")
     * @ORM\JoinColumn(name="programacion_id", referencedColumnName="id")
     */
    private $programacion;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Asistencia
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuario
     *
     * @return Asistencia
     */
    public function setUsuario(\Application\Sonata\UserBundle\Entity\User $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set oferta
     *
     * @param \LogicBundle\Entity\Oferta $oferta
     *
     * @return Asistencia
     */
    public function setOferta(\LogicBundle\Entity\Oferta $oferta = null)
    {
        $this->oferta = $oferta;

        return $this;
    }

    /**
     * Get oferta
     *
     * @return \LogicBundle\Entity\Oferta
     */
    public function getOferta()
    {
        return $this->oferta;
    }

    /**
     * Set programacion
     *
     * @param \LogicBundle\Entity\Programacion $programacion
     *
     * @return Asistencia
     */
    public function setProgramacion(\LogicBundle\Entity\Programacion $programacion = null)
    {
        $this->programacion = $programacion;

        return $this;
    }

    /**
     * Get programacion
     *
     * @return \LogicBundle\Entity\Programacion
     */
    public function getProgramacion()
    {
        return $this->programacion;
    }

    /**
     * Set asistio
     *
     * @param boolean $asistio
     *
     * @return Asistencia
     */
    public function setAsistio($asistio)
    {
        $this->asistio = $asistio;

        return $this;
    }

    /**
     * Get asistio
     *
     * @return boolean
     */
    public function getAsistio()
    {
        return $this->asistio;
    }
}
