<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * DisciplinaPruebaRama
 *
 * @ORM\Table(name="disciplina_prueba_rama")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DisciplinaPruebaRamaRepository")
 */
class DisciplinaPruebaRama
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Disciplina", inversedBy="disciplinasPruebasRamas")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    private $disciplina;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Prueba", inversedBy="disciplinasPruebasRamas")
     * @ORM\JoinColumn(name="prueba_id", referencedColumnName="id")
     */
    private $prueba;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Rama", inversedBy="disciplinasPruebasRamas")
     * @ORM\JoinColumn(name="rama_id", referencedColumnName="id")
     */
    private $rama;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\Medicion", mappedBy="disciplinaPruebaRama")
     */
    private $mediciones;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mediciones = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * Set disciplina
     *
     * @param \LogicBundle\Entity\Disciplina $disciplina
     *
     * @return DisciplinaPruebaRama
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
     * Set prueba
     *
     * @param \LogicBundle\Entity\Prueba $prueba
     *
     * @return DisciplinaPruebaRama
     */
    public function setPrueba(\LogicBundle\Entity\Prueba $prueba = null) {
        $this->prueba = $prueba;

        return $this;
    }

    /**
     * Get prueba
     *
     * @return \LogicBundle\Entity\Prueba
     */
    public function getPrueba() {
        return $this->prueba;
    }

    /**
     * Set rama
     *
     * @param \LogicBundle\Entity\Rama $rama
     *
     * @return DisciplinaPruebaRama
     */
    public function setRama(\LogicBundle\Entity\Rama $rama = null) {
        $this->rama = $rama;

        return $this;
    }

    /**
     * Get rama
     *
     * @return \LogicBundle\Entity\Rama
     */
    public function getRama() {
        return $this->rama;
    }

    /**
     * Add medicion
     *
     * @param \LogicBundle\Entity\Medicion $medicion
     *
     * @return DisciplinaPruebaRama
     */
    public function addMedicion(\LogicBundle\Entity\Medicion $medicion)
    {
        $this->mediciones[] = $medicion;

        return $this;
    }

    /**
     * Remove medicion
     *
     * @param \LogicBundle\Entity\Medicion $medicion
     */
    public function removeMedicion(\LogicBundle\Entity\Medicion $medicion)
    {
        $this->mediciones->removeElement($medicion);
    }

    /**
     * Get mediciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediciones()
    {
        return $this->mediciones;
    }

    /**
     * Add medicione.
     *
     * @param \LogicBundle\Entity\Medicion $medicione
     *
     * @return DisciplinaPruebaRama
     */
    public function addMedicione(\LogicBundle\Entity\Medicion $medicione)
    {
        $this->mediciones[] = $medicione;

        return $this;
    }

    /**
     * Remove medicione.
     *
     * @param \LogicBundle\Entity\Medicion $medicione
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMedicione(\LogicBundle\Entity\Medicion $medicione)
    {
        return $this->mediciones->removeElement($medicione);
    }
}
