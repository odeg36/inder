<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recordatorio
 *
 * @ORM\Table(name="recordatorio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RecordatorioRepository")
 */
class Recordatorio
{
    public function __toString() {
        return (string)$this->getNutricion()->getDeportista() ? : '';
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
     * @ORM\OneToOne(targetEntity="ConsultaNutricion", inversedBy="recordatorio")
     * @ORM\JoinColumn(name="nutricion_id", referencedColumnName="id")
     */
    private $nutricion;
    
    /**
     * @ORM\OneToMany(targetEntity="RecordatorioComida", mappedBy="recordatorio", cascade={"persist"})
     */
    private $recordatorioComidas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recordatorioComidas = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nutricion
     *
     * @param \LogicBundle\Entity\ConsultaNutricion $nutricion
     *
     * @return Recordatorio
     */
    public function setNutricion(\LogicBundle\Entity\ConsultaNutricion $nutricion = null)
    {
        $this->nutricion = $nutricion;

        return $this;
    }

    /**
     * Get nutricion
     *
     * @return \LogicBundle\Entity\ConsultaNutricion
     */
    public function getNutricion()
    {
        return $this->nutricion;
    }

    /**
     * Add recordatorioComida
     *
     * @param \LogicBundle\Entity\RecordatorioComida $recordatorioComida
     *
     * @return Recordatorio
     */
    public function addRecordatorioComida(\LogicBundle\Entity\RecordatorioComida $recordatorioComida)
    {
        $this->recordatorioComidas[] = $recordatorioComida;

        return $this;
    }

    /**
     * Remove recordatorioComida
     *
     * @param \LogicBundle\Entity\RecordatorioComida $recordatorioComida
     */
    public function removeRecordatorioComida(\LogicBundle\Entity\RecordatorioComida $recordatorioComida)
    {
        $this->recordatorioComidas->removeElement($recordatorioComida);
    }

    /**
     * Get recordatorioComidas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecordatorioComidas()
    {
        return $this->recordatorioComidas;
    }
}
