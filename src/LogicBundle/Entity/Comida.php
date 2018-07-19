<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comida
 *
 * @ORM\Table(name="comida")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ComidaRepository")
 */
class Comida
{
    
    public function __toString() {
        return $this->getNombre() ? : '';
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
     * @ORM\Column(name="nombre", type="string", length=30)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RecordatorioComida", mappedBy="comida")
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Comida
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
     * Add recordatorioComida
     *
     * @param \LogicBundle\Entity\RecordatorioComida $recordatorioComida
     *
     * @return Comida
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
