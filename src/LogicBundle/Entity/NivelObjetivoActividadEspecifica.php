<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NivelObjetivoActividadEspecifica
 *
 * @ORM\Table(name="nivel_objetivo_actividad_especifica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\NivelObjetivoActividadEspecificaRepository")
 */
class NivelObjetivoActividadEspecifica
{
    public function __toString() {
        return $this->getObjetivo() ? : '';
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
     * @ORM\Column(name="actividad", type="text")
     */
    private $actividad;

    /**
     * @ORM\ManyToOne(targetEntity="NivelSubPrincipioObjetivo", inversedBy="objetivoActividadEspecificas", cascade={"persist"})
     * @ORM\JoinColumn(name="objetivo_id", referencedColumnName="id")
     */
    private $objetivo;

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
     * Set actividad.
     *
     * @param string $actividad
     *
     * @return NivelObjetivoActividadEspecifica
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Get actividad.
     *
     * @return string
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set objetivo.
     *
     * @param \LogicBundle\Entity\NivelSubPrincipioObjetivo|null $objetivo
     *
     * @return NivelObjetivoActividadEspecifica
     */
    public function setObjetivo(\LogicBundle\Entity\NivelSubPrincipioObjetivo $objetivo = null)
    {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo.
     *
     * @return \LogicBundle\Entity\NivelSubPrincipioObjetivo|null
     */
    public function getObjetivo()
    {
        return $this->objetivo;
    }
}
