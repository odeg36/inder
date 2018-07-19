<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Accion
 *
 * @ORM\Table(name="campo_usuario")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CampoExtraUsuarioRepository")
 */
class CampoUsuario {

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
     * @var string
     *
     * @ORM\Column(name="nombre_mapeado", type="string", length=255)
     */
    private $nombreMapeado;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255,nullable=true)
     */
    private $tipo;

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
    
    /**
     * @ORM\OneToMany(targetEntity="EstrategiaCampo", mappedBy="campoUsuario")
     */
    private $campoUsuarios;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campoUsuarios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CampoUsuario
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
     * Set nombreMapeado
     *
     * @param string $nombreMapeado
     *
     * @return CampoUsuario
     */
    public function setNombreMapeado($nombreMapeado)
    {
        $this->nombreMapeado = $nombreMapeado;

        return $this;
    }

    /**
     * Get nombreMapeado
     *
     * @return string
     */
    public function getNombreMapeado()
    {
        return $this->nombreMapeado;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return CampoUsuario
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return CampoUsuario
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return CampoUsuario
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Add campoUsuario
     *
     * @param \LogicBundle\Entity\EstrategiaCampo $campoUsuario
     *
     * @return CampoUsuario
     */
    public function addCampoUsuario(\LogicBundle\Entity\EstrategiaCampo $campoUsuario)
    {
        $this->campoUsuarios[] = $campoUsuario;

        return $this;
    }

    /**
     * Remove campoUsuario
     *
     * @param \LogicBundle\Entity\EstrategiaCampo $campoUsuario
     */
    public function removeCampoUsuario(\LogicBundle\Entity\EstrategiaCampo $campoUsuario)
    {
        $this->campoUsuarios->removeElement($campoUsuario);
    }

    /**
     * Get campoUsuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampoUsuarios()
    {
        return $this->campoUsuarios;
    }
}
