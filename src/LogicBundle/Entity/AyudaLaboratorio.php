<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AyudaLaboratorio
 *
 * @ORM\Table(name="ayuda_laboratorio")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\AyudaLaboratorioRepository")
 */
class AyudaLaboratorio {

    public function __toString() {
        return $this->getNombre() ?: '';
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Laboratorio", mappedBy="ayudaLaboratorio")
     */
    private $laboratorios;

    /**
     * @ORM\OneToMany(targetEntity="LaboratorioMedico", mappedBy="ayudaDxParaclinico")
     */
    private $laboratoriosMedicos;

    /**
     * @ORM\OneToMany(targetEntity="LaboratorioHojaEvolucion", mappedBy="ayudaParaclinico")
     */
    private $laboratoriosHojasEvoluciones;

    /**
     * Constructor
     */
    public function __construct() {
        $this->laboratorios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laboratoriosMedicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laboratoriosHojasEvoluciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AyudaLaboratorio
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add laboratorio
     *
     * @param \LogicBundle\Entity\Laboratorio $laboratorio
     *
     * @return AyudaLaboratorio
     */
    public function addLaboratorio(\LogicBundle\Entity\Laboratorio $laboratorio) {
        $this->laboratorios[] = $laboratorio;

        return $this;
    }

    /**
     * Remove laboratorio
     *
     * @param \LogicBundle\Entity\Laboratorio $laboratorio
     */
    public function removeLaboratorio(\LogicBundle\Entity\Laboratorio $laboratorio) {
        $this->laboratorios->removeElement($laboratorio);
    }

    /**
     * Get laboratorios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratorios() {
        return $this->laboratorios;
    }

    /**
     * Add laboratoriosMedico
     *
     * @param \LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico
     *
     * @return AyudaLaboratorio
     */
    public function addLaboratoriosMedico(\LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico) {
        $this->laboratoriosMedicos[] = $laboratoriosMedico;

        return $this;
    }

    /**
     * Remove laboratoriosMedico
     *
     * @param \LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico
     */
    public function removeLaboratoriosMedico(\LogicBundle\Entity\LaboratorioMedico $laboratoriosMedico) {
        $this->laboratoriosMedicos->removeElement($laboratoriosMedico);
    }

    /**
     * Get laboratoriosMedicos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratoriosMedicos() {
        return $this->laboratoriosMedicos;
    }

    /**
     * Add laboratoriosHojasEvolucione
     *
     * @param \LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione
     *
     * @return AyudaLaboratorio
     */
    public function addLaboratoriosHojasEvolucione(\LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione) {
        $this->laboratoriosHojasEvoluciones[] = $laboratoriosHojasEvolucione;

        return $this;
    }

    /**
     * Remove laboratoriosHojasEvolucione
     *
     * @param \LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione
     */
    public function removeLaboratoriosHojasEvolucione(\LogicBundle\Entity\LaboratorioHojaEvolucion $laboratoriosHojasEvolucione) {
        $this->laboratoriosHojasEvoluciones->removeElement($laboratoriosHojasEvolucione);
    }

    /**
     * Get laboratoriosHojasEvoluciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaboratoriosHojasEvoluciones() {
        return $this->laboratoriosHojasEvoluciones;
    }

}
