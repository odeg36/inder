<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ObservacionEspecialidad
 *
 * @ORM\Table(name="observacion_especialidad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ObservacionEspecialidadRepository")
 */
class ObservacionEspecialidad {

    public function __toString() {
        return (string) $this->getDeportista() ?: '';
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
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="observacionEspecialidades")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;

    /**
     * @var string
     *
     * @ORM\Column(name="medico", type="text")
     */
    private $medico;

    /**
     * @var string
     *
     * @ORM\Column(name="nutricionista", type="text")
     */
    private $nutricionista;

    /**
     * @var string
     *
     * @ORM\Column(name="psicologo", type="text")
     */
    private $psicologo;

    /**
     * @var string
     *
     * @ORM\Column(name="fisioterapeuta", type="text")
     */
    private $fisioterapeuta;

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set medico
     *
     * @param string $medico
     *
     * @return ObservacionEspecialidad
     */
    public function setMedico($medico) {
        $this->medico = $medico;

        return $this;
    }

    /**
     * Get medico
     *
     * @return string
     */
    public function getMedico() {
        return $this->medico;
    }

    /**
     * Set nutricionista
     *
     * @param string $nutricionista
     *
     * @return ObservacionEspecialidad
     */
    public function setNutricionista($nutricionista) {
        $this->nutricionista = $nutricionista;

        return $this;
    }

    /**
     * Get nutricionista
     *
     * @return string
     */
    public function getNutricionista() {
        return $this->nutricionista;
    }

    /**
     * Set psicologo
     *
     * @param string $psicologo
     *
     * @return ObservacionEspecialidad
     */
    public function setPsicologo($psicologo) {
        $this->psicologo = $psicologo;

        return $this;
    }

    /**
     * Get psicologo
     *
     * @return string
     */
    public function getPsicologo() {
        return $this->psicologo;
    }

    /**
     * Set fisioterapeuta
     *
     * @param string $fisioterapeuta
     *
     * @return ObservacionEspecialidad
     */
    public function setFisioterapeuta($fisioterapeuta) {
        $this->fisioterapeuta = $fisioterapeuta;

        return $this;
    }

    /**
     * Get fisioterapeuta
     *
     * @return string
     */
    public function getFisioterapeuta() {
        return $this->fisioterapeuta;
    }

    /**
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ObservacionEspecialidad
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return ObservacionEspecialidad
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
     * @return ObservacionEspecialidad
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
}
