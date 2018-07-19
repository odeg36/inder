<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RestriccionDisciplina
 *
 * @ORM\Table(name="restriccion_disciplina")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RestriccionDisciplinaRepository")
 */
class RestriccionDisciplina
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
     * @var bool
     *
     * @ORM\Column(name="necesita_acompañante", type="boolean")
     */
    private $necesitaAcompañante;

    /**
     * @var int
     *
     * @ORM\Column(name="edad_maxima_acompanar", type="integer")
     */
    private $edadMaximaAcompanar;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id", )
     */
    private $disciplina;

    /**
     * Constructor
     */
    public function __construct() {
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
     * Set necesitaAcompañante
     *
     * @param boolean $necesitaAcompañante
     *
     * @return RestriccionDiciplina
     */
    public function setNecesitaAcompañante($necesitaAcompañante)
    {
        $this->necesitaAcompañante = $necesitaAcompañante;

        return $this;
    }

    /**
     * Get necesitaAcompañante
     *
     * @return bool
     */
    public function getNecesitaAcompañante()
    {
        return $this->necesitaAcompañante;
    }

    /**
     * Set edadMaximaAcompanar
     *
     * @param integer $edadMaximaAcompanar
     *
     * @return RestriccionDiciplina
     */
    public function setEdadMaximaAcompanar($edadMaximaAcompanar)
    {
        $this->edadMaximaAcompanar = $edadMaximaAcompanar;

        return $this;
    }

    /**
     * Get edadMaximaAcompanar
     *
     * @return int
     */
    public function getEdadMaximaAcompanar()
    {
        return $this->edadMaximaAcompanar;
    }

    /**
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return Division
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
