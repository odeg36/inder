<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="estabilidad_lumbo_pelvica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\EstabilidadLumboPelvicaRepository")
 */
class EstabilidadLumboPelvica {

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
     * @ORM\OneToMany(targetEntity="PruebaEstabilidad", mappedBy="estabilidadLumboPelvica", cascade={"persist"}, orphanRemoval=true)
     */
    private $pruebasEstabilidad;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaFisioterapia", inversedBy="estabilidadLumboPelvica")
     * @ORM\JoinColumn(name="consulta_fisioterapia_id", referencedColumnName="id")
     */
    private $consultaFisioterapia;

    /**
     * Add pruebasEstabilidad
     *
     * @param \LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad
     *
     * @return EstabilidadLumboPelvica
     */
    public function addPruebasEstabilidad(\LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad) {
        $pruebasEstabilidad->setEstabilidadLumboPelvica($this);
        $this->pruebasEstabilidad[] = $pruebasEstabilidad;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Constructor
     */
    public function __construct() {
        $this->pruebasEstabilidad = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Remove pruebasEstabilidad
     *
     * @param \LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad
     */
    public function removePruebasEstabilidad(\LogicBundle\Entity\PruebaEstabilidad $pruebasEstabilidad) {
        $this->pruebasEstabilidad->removeElement($pruebasEstabilidad);
    }

    /**
     * Get pruebasEstabilidad
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPruebasEstabilidad() {
        return $this->pruebasEstabilidad;
    }

    /**
     * Set consultaFisioterapia
     *
     * @param \LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia
     *
     * @return EstabilidadLumboPelvica
     */
    public function setConsultaFisioterapia(\LogicBundle\Entity\ConsultaFisioterapia $consultaFisioterapia = null) {
        $this->consultaFisioterapia = $consultaFisioterapia;

        return $this;
    }

    /**
     * Get consultaFisioterapia
     *
     * @return \LogicBundle\Entity\ConsultaFisioterapia
     */
    public function getConsultaFisioterapia() {
        return $this->consultaFisioterapia;
    }

}
