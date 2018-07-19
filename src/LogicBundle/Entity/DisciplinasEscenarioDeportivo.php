<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DisciplinasEscenarioDeportivo
 *
 * @ORM\Table(name="disciplinas_escenario_deportivo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DisciplinasEscenarioDeportivoRepository")
 */
class DisciplinasEscenarioDeportivo
{

    public function __toString() {
        return $this->disciplina->getNombre() ? $this->disciplina->getNombre() : '';
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo", inversedBy="disciplinasEscenarioDeportivos")
     * @ORM\JoinColumn(name="escenario_deportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;
    
    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina",  inversedBy="disciplinas")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id", )
     */
    private $disciplina;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->escenarioDeportivo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disciplina = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return UsuarioEscenarioDeportivo
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null) {
        $this->escenarioDeportivo = $escenarioDeportivo;
        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo() {
        return $this->escenarioDeportivo;
    }


    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return DisciplinasEscenarioDeportivo
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
}
