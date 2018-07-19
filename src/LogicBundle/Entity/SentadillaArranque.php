<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SetAndSearch
 *
 * @ORM\Table(name="sentadilla_arranque")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\SentadillaArranqueRepository")
 */
class SentadillaArranque {

    public function __toString() {
        return (string) $this->getId() ?: '';
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
     * @ORM\OneToMany(targetEntity="PruebaSentadillaArranque", mappedBy="sentadillaArranque", cascade={"persist"}, orphanRemoval=true)
     */
    private $pruebasSentadillaArranque;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="sentadillaArranque")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;

    /**
     * Add pruebasSentadillaArranque
     *
     * @param \LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque
     *
     * @return SentadillaArranque
     */
    public function addPruebasSentadillaArranque(\LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque) {
        $pruebasSentadillaArranque->setSentadillaArranque($this);
        $this->pruebasSentadillaArranque[] = $pruebasSentadillaArranque;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pruebasSentadillaArranque = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Remove pruebasSentadillaArranque
     *
     * @param \LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque
     */
    public function removePruebasSentadillaArranque(\LogicBundle\Entity\PruebaSentadillaArranque $pruebasSentadillaArranque)
    {
        $this->pruebasSentadillaArranque->removeElement($pruebasSentadillaArranque);
    }

    /**
     * Get pruebasSentadillaArranque
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasSentadillaArranque()
    {
        return $this->pruebasSentadillaArranque;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return SentadillaArranque
     */
    public function setConsultaFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia = null)
    {
        $this->consultaFisioterapia = $consultaFisioterapia;

        return $this;
    }

    /**
     * Get consultaFisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getConsultaFisioterapia()
    {
        return $this->consultaFisioterapia;
    }
}
