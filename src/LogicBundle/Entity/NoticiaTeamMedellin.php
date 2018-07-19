<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NoticiaTeamMedellin
 *
 * @ORM\Table(name="noticia_team_medellin")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NoticiaTeamMedellinRepository")
 */
class NoticiaTeamMedellin {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcionNoticia", type="text")
     */
    private $descripcionNoticia;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean")
     */
    private $activo;

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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Patrocinadores")
     * @ORM\JoinColumn(name="patrocinadores_id", referencedColumnName="id", )
     */
    private $patrocinador;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id", )
     */
    private $deporte;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255)
     */
    private $video;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\ImagenesNoticiasTeamMedellin", mappedBy="noticiaTeamMedellin", cascade={"persist"})
     */
    private $imagenesNoticiasTeamMedellin;

    /**
     * @var \DateTime $fecha
     *
     * @ORM\Column(name="fecha", type="date")
     */
    protected $fecha;

    /**
     * Constructor
     */
    public function __construct() {
        $this->imagenesNoticiasTeamMedellin = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return NoticiaTeamMedellin
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set video.
     *
     * @param string $video
     *
     * @return NoticiaTeamMedellin
     */
    public function setVideo($video) {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video.
     *
     * @return string
     */
    public function getVideo() {
        return $this->video;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return NoticiaTeamMedellin
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set descripcionNoticia.
     *
     * @param string $descripcionNoticia
     *
     * @return NoticiaTeamMedellin
     */
    public function setDescripcionNoticia($descripcionNoticia) {
        $this->descripcionNoticia = $descripcionNoticia;

        return $this;
    }

    /**
     * Get descripcionNoticia.
     *
     * @return string
     */
    public function getDescripcionNoticia() {
        return $this->descripcionNoticia;
    }

    /**
     * Set activo.
     *
     * @param bool $activo
     *
     * @return NoticiaTeamMedellin
     */
    public function setActivo($activo) {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo.
     *
     * @return bool
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return NotaNutricion
     */
    public function setFechaCreacion($fechaCreacion) {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return NotaNutricion
     */
    public function setFechaActualizacion($fechaActualizacion) {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion() {
        return $this->fechaActualizacion;
    }

    /**
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return NoticiaTeamMedellin
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null) {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista() {
        return $this->deportista;
    }

    /**
     * Set patrocinador
     *
     * @param \LogicBundle\Entity\Patrocinadores $patrocinador
     *
     * @return NoticiaTeamMedellin
     */
    public function setPatrocinador(\LogicBundle\Entity\Patrocinadores $patrocinador = null) {
        $this->patrocinador = $patrocinador;

        return $this;
    }

    /**
     * Get patrocinador
     *
     * @return \LogicBundle\Entity\Patrocinadores
     */
    public function getPatrocinador() {
        return $this->patrocinador;
    }

    /**
     * Set deporte
     *
     * @param \LogicBundle\Entity\Disciplina $deporte
     *
     * @return NoticiaTeamMedellin
     */
    public function setDeporte(\LogicBundle\Entity\Disciplina $deporte = null) {
        $this->deporte = $deporte;

        return $this;
    }

    /**
     * Get deporte
     *
     * @return \LogicBundle\Entity\Disciplina
     */
    public function getDeporte() {
        return $this->deporte;
    }

    /**
     * Get imagenesNoticiasTeamMedellin
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImagenesNoticiasTeamMedellin() {
        return $this->imagenesNoticiasTeamMedellin;
    }

    /**
     * Add imagenesNoticiasTeamMedellin
     *
     * @param \LogicBundle\Entity\ImagenesNoticiasTeamMedellin $imagenesNoticiasTeamMedellin
     *
     * @return NoticiaTeamMedellin
     */
    public function addImagenesNoticiasTeamMedellin(\LogicBundle\Entity\ImagenesNoticiasTeamMedellin $imagenesNoticiasTeamMedellin) {
        $this->imagenesNoticiasTeamMedellin[] = $imagenesNoticiasTeamMedellin;

        return $this;
    }

    /**
     * Remove imagenesNoticiasTeamMedellin
     *
     * @param \LogicBundle\Entity\ImagenesNoticiasTeamMedellin $imagenesNoticiasTeamMedellin
     */
    public function removeImagenesNoticiasTeamMedellin(\LogicBundle\Entity\ImagenesNoticiasTeamMedellin $imagenesNoticiasTeamMedellin) {
        $this->imagenesNoticiasTeamMedellin->removeElement($imagenesNoticiasTeamMedellin);
    }

}
