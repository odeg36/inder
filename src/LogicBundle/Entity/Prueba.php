<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Prueba
 *
 * @ORM\Table(name="prueba")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaRepository")
 */
class Prueba
{

    /**
     * @ORM\OneToMany(targetEntity="Medicion", mappedBy="prueba")
     */
    private $mediciones;


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
     * @ORM\Column(name="nombre", type="text")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="bibliografia", type="text", nullable=true)
     */
    private $bibliografia;

    /**
     * @var string
     *
     * @ORM\Column(name="valoresEntrada", type="text")
     */
    private $valoresEntrada;

    /**
     * @var string
     *
     * @ORM\Column(name="valoresSalida", type="text")
     */
    private $valoresSalida;

    /**
     * @ORM\OneToMany(targetEntity="LogicBundle\Entity\DisciplinaPruebaRama", mappedBy="prueba")
     */
    private $disciplinasPruebasRamas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->disciplinasPruebasRamas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mediciones = new \Doctrine\Common\Collections\ArrayCollection();
        
    }

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

     ////************ FIN MODIFICACIONES ********////////////


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
     * @return Prueba
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
     * Set bibliografia
     *
     * @param string $bibliografia
     *
     * @return Prueba
     */
    public function setBibliografia($bibliografia)
    {
        $this->bibliografia = $bibliografia;

        return $this;
    }

    /**
     * Get bibliografia
     *
     * @return string
     */
    public function getBibliografia()
    {
        return $this->bibliografia;
    }

    /**
     * Set valoresEntrada
     *
     * @param string $valoresEntrada
     *
     * @return Prueba
     */
    public function setValoresEntrada($valoresEntrada)
    {
        $this->valoresEntrada = $valoresEntrada;

        return $this;
    }

    /**
     * Get valoresEntrada
     *
     * @return string
     */
    public function getValoresEntrada()
    {
        return $this->valoresEntrada;
    }

    /**
     * Set valoresSalida
     *
     * @param string $valoresSalida
     *
     * @return Prueba
     */
    public function setValoresSalida($valoresSalida)
    {
        $this->valoresSalida = $valoresSalida;

        return $this;
    }

    /**
     * Get valoresSalida
     *
     * @return string
     */
    public function getValoresSalida()
    {
        return $this->valoresSalida;
    }

    /**
     * Add medicione
     *
     * @param \LogicBundle\Entity\Medicion $medicione
     *
     * @return Prueba
     */
    public function addMedicione(\LogicBundle\Entity\Medicion $medicione)
    {
        $this->mediciones[] = $medicione;

        return $this;
    }

    /**
     * Remove medicione
     *
     * @param \LogicBundle\Entity\Medicion $medicione
     */
    public function removeMedicione(\LogicBundle\Entity\Medicion $medicione)
    {
        $this->mediciones->removeElement($medicione);
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
     * Add disciplinasPruebasRama
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     *
     * @return Prueba
     */
    public function addDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama)
    {
        $this->disciplinasPruebasRamas[] = $disciplinasPruebasRama;

        return $this;
    }

    /**
     * Remove disciplinasPruebasRama
     *
     * @param \LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama
     */
    public function removeDisciplinasPruebasRama(\LogicBundle\Entity\DisciplinaPruebaRama $disciplinasPruebasRama)
    {
        $this->disciplinasPruebasRamas->removeElement($disciplinasPruebasRama);
    }

    /**
     * Get disciplinasPruebasRamas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinasPruebasRamas()
    {
        return $this->disciplinasPruebasRamas;
    }
}
