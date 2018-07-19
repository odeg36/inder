<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * PerfilCuerpoTecnico
 *
 * @ORM\Table(name="perfil_cuerpo_tecnico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PerfilCuerpoTecnicoRepository")
 */
class PerfilCuerpoTecnico
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_entrenador", type="string", length=255)
     */
    private $nombreEntrenador;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_experiencia", type="text")
     */
    private $nombreExperiencia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_experiencia", type="datetime")
     */
    private $fechaExperiencia;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;


    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="perfilCuerpoTecnicos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $deportista;

    /**
     * @var \DateTime $fechaCreacion
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    protected $fechaCreacion;

    /**
     * @var \DateTime $fechaActualizacion
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="date")
     */
    protected $fechaActualizacion;
    
    public function __toString() {
        return $this->nombreExperiencia ? $this->nombreExperiencia : '';
    }


    ////************ FIN MODIFICACIONES ********////////////



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreEntrenador.
     *
     * @param string $nombreEntrenador
     *
     * @return PerfilCuerpoTecnico
     */
    public function setNombreEntrenador($nombreEntrenador)
    {
        $this->nombreEntrenador = $nombreEntrenador;

        return $this;
    }

    /**
     * Get nombreEntrenador.
     *
     * @return string
     */
    public function getNombreEntrenador()
    {
        return $this->nombreEntrenador;
    }

    /**
     * Set nombreExperiencia.
     *
     * @param string $nombreExperiencia
     *
     * @return PerfilCuerpoTecnico
     */
    public function setNombreExperiencia($nombreExperiencia)
    {
        $this->nombreExperiencia = $nombreExperiencia;

        return $this;
    }

    /**
     * Get nombreExperiencia.
     *
     * @return string
     */
    public function getNombreExperiencia()
    {
        return $this->nombreExperiencia;
    }

    /**
     * Set fechaExperiencia.
     *
     * @param \DateTime $fechaExperiencia
     *
     * @return PerfilCuerpoTecnico
     */
    public function setFechaExperiencia($fechaExperiencia)
    {
        $this->fechaExperiencia = $fechaExperiencia;

        return $this;
    }

    /**
     * Get fechaExperiencia.
     *
     * @return \DateTime
     */
    public function getFechaExperiencia()
    {
        return $this->fechaExperiencia;
    }

    /**
     * Set observaciones.
     *
     * @param string $observaciones
     *
     * @return PerfilCuerpoTecnico
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones.
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return PerfilCuerpoTecnico
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return PerfilCuerpoTecnico
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set deportista.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $deportista
     *
     * @return PerfilCuerpoTecnico
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getDeportista()
    {
        return $this->deportista;
    }
}
