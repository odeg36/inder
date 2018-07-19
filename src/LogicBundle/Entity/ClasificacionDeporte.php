<?php

namespace LogicBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * ClasificacionDeporte
 *
 * @ORM\Table(name="clasificacion_deporte")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ClasificacionDeporteRepository")
 */
class ClasificacionDeporte {

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
     * @Gedmo\Slug(fields={"nombre"}, style="lower", separator="_")
     * @ORM\Column(length=128, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @var bool
     *
     * @ORM\Column(name="plan_metodologico", type="boolean", nullable=true)
     */
    private $planMetodologico;
    
    /**
     * @ORM\ManyToMany(targetEntity="Disciplina", inversedBy="clasificaciones")
     * @ORM\JoinTable(name="clasificaciones__disciplinas",
     *      joinColumns={@ORM\JoinColumn(name="clasificacion_deporte_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")})
     */
    private $disciplinas;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\PlanMetodologico", mappedBy="clasificacion")
     */
    private $planMetodologicos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->disciplinas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->planMetodologicos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return ClasificacionDeporte
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return ClasificacionDeporte
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set planMetodologico.
     *
     * @param bool|null $planMetodologico
     *
     * @return ClasificacionDeporte
     */
    public function setPlanMetodologico($planMetodologico = null)
    {
        $this->planMetodologico = $planMetodologico;

        return $this;
    }

    /**
     * Get planMetodologico.
     *
     * @return bool|null
     */
    public function getPlanMetodologico()
    {
        return $this->planMetodologico;
    }

    /**
     * Add disciplina.
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return ClasificacionDeporte
     */
    public function addDisciplina(\LogicBundle\Entity\Disciplina $disciplina)
    {
        $this->disciplinas[] = $disciplina;

        return $this;
    }

    /**
     * Remove disciplina.
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDisciplina(\LogicBundle\Entity\Disciplina $disciplina)
    {
        return $this->disciplinas->removeElement($disciplina);
    }

    /**
     * Get disciplinas.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinas()
    {
        return $this->disciplinas;
    }

    /**
     * Add planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return ClasificacionDeporte
     */
    public function addPlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico)
    {
        $this->planMetodologicos[] = $planMetodologico;

        return $this;
    }

    /**
     * Remove planMetodologico.
     *
     * @param \LogicBundle\Entity\PlanMetodologico $planMetodologico
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlanMetodologico(\LogicBundle\Entity\PlanMetodologico $planMetodologico)
    {
        return $this->planMetodologicos->removeElement($planMetodologico);
    }

    /**
     * Get planMetodologicos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanMetodologicos()
    {
        return $this->planMetodologicos;
    }
}
