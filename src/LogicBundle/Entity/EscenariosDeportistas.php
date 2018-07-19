<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EscenariosDeportistas
 *
 * @ORM\Table(name="escenarios_deportistas")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EscenariosDeportistasRepository")
 */
class EscenariosDeportistas
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
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\EscenarioDeportivo")
     * @ORM\JoinColumn(name="escenarioDeportivo_id", referencedColumnName="id", )
     */
    private $escenarioDeportivo;


    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Barrio")
     * @ORM\JoinColumn(name="barrio_id", referencedColumnName="id", )
     */
    private $barrio;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id", )
     */
    private $municipio;


    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="deportista_id", referencedColumnName="id")
     */
    private $deportista;
    

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
     * Set deportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $deportista
     *
     * @return ConsultaNutricion
     */
    public function setDeportista(\Application\Sonata\UserBundle\Entity\User $deportista = null)
    {
        $this->deportista = $deportista;

        return $this;
    }

    /**
     * Get deportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDeportista()
    {
        return $this->deportista;
    }


    /**
     * Set escenarioDeportivo
     *
     * @param \LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo
     *
     * @return EscenariosDeportistas
     */
    public function setEscenarioDeportivo(\LogicBundle\Entity\EscenarioDeportivo $escenarioDeportivo = null)
    {
        $this->escenarioDeportivo = $escenarioDeportivo;

        return $this;
    }

    /**
     * Get escenarioDeportivo
     *
     * @return \LogicBundle\Entity\EscenarioDeportivo
     */
    public function getEscenarioDeportivo()
    {
        return $this->escenarioDeportivo;
    }


      /**
     * Set barrio
     *
     * @param \LogicBundle\Entity\Barrio $barrio
     *
     * @return EscenariosDeportistas
     */
    public function setBarrio(\LogicBundle\Entity\Barrio $barrio = null)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return \LogicBundle\Entity\Barrio
     */
    public function getBarrio()
    {
        return $this->barrio;
    }




      /**
     * Set municipio
     *
     * @param \LogicBundle\Entity\Municipio $municipio
     *
     * @return EscenariosDeportistas
     */
    public function setMunicipio(\LogicBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \LogicBundle\Entity\Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
}
