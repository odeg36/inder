<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisciplinaOrganizacion
 *
 * @ORM\Table(name="disciplina_organizacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DisciplinaOrganizacionRepository")
 */
class DisciplinaOrganizacion {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="organizacionesdeportivas")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $disciplina;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\OrganizacionDeportiva", inversedBy="disciplinaOrganizaciones", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $organizacion;

    /**
     * @ORM\OneToMany(targetEntity="OrganismoDeportista", mappedBy="disciplinaOrganizacion", cascade={"persist", "remove"})
     */
    protected $deportistas;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return DisciplinaOrganizacion
     */
    public function setDisciplina(\LogicBundle\Entity\Disciplina $disciplina = null) {
        $this->disciplina = $disciplina;

        return $this;
    }

    /**
     * Get disciplina
     *
     * @return \LogicBundle\Entity\Disciplina
     */
    public function getDisciplina() {
        return $this->disciplina;
    }

    /**
     * Set organizacion
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacion
     *
     * @return DisciplinaOrganizacion
     */
    public function setOrganizacion(\LogicBundle\Entity\OrganizacionDeportiva $organizacion = null) {
        $this->organizacion = $organizacion;

        return $this;
    }

    /**
     * Get organizacion
     *
     * @return \LogicBundle\Entity\OrganizacionDeportiva
     */
    public function getOrganizacion() {
        return $this->organizacion;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->deportistas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add deportista
     *
     * @param \LogicBundle\Entity\OrganismoDeportista $deportista
     *
     * @return DisciplinaOrganizacion
     */
    public function addDeportista(\LogicBundle\Entity\OrganismoDeportista $deportista) {
        $deportista->setDisciplinaOrganizacion($this);
        $this->deportistas[] = $deportista;

        return $this;
    }

    /**
     * Remove deportista
     *
     * @param \LogicBundle\Entity\OrganismoDeportista $deportista
     */
    public function removeDeportista(\LogicBundle\Entity\OrganismoDeportista $deportista) {
        $this->deportistas->removeElement($deportista);
    }

    /**
     * Get deportistas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeportistas() {
        return $this->deportistas;
    }

}
