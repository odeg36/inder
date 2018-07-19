<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaDisiplina
 *
 * @ORM\Table(name="categoria_disciplina")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\CategoriaDisciplinaRepository")
 */
class CategoriaDisciplina {

    protected $datagridValues = [
        '_sort_by' => 'nombre',
    ];

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
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Disciplina", mappedBy="categoriaDisciplina")
     */
    private $disciplinas;

    /**
     * Constructor
     */
    public function __construct() {
        $this->disiplinas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CategoriaDisiplina
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return Evento
     */
    public function addDisciplina(\LogicBundle\Entity\Disciplina $disciplina) {
        $this->disciplinas[] = $disciplina;

        return $this;
    }

    /**
     * Remove disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     */
    public function removeDisciplina(\LogicBundle\Entity\Disciplina $disciplina) {
        $this->disciplinas->removeElement($disciplina);
    }

    /**
     * Get Disciplinas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplina() {
        return $this->disciplinas;
    }

    /**
     * Get disciplinas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinas() {
        return $this->disciplinas;
    }

}
