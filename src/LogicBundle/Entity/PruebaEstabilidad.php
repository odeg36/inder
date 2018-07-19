<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="prueba_estabilidad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaEstabilidadRepository")
 */
class PruebaEstabilidad {

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
     * @ORM\ManyToOne(targetEntity="EstabilidadLumboPelvica", inversedBy="pruebasEstabilidad")
     * @ORM\JoinColumn(name="estabilidad_lumbo_pelvica_id", referencedColumnName="id")
     */
    private $estabilidadLumboPelvica;

    /**
     * @ORM\ManyToOne(targetEntity="PruebaLumboPelvica", inversedBy="pruebasEstabilidad")
     * @ORM\JoinColumn(name="prueba_lumbo_pelvica_id", referencedColumnName="id")
     */
    private $pruebaLumboPelvica;

    /**
     * @ORM\OneToMany(targetEntity="PuntuacionPruebaLumboPelvica", mappedBy="pruebaLumboPelvica", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $puntuaciones;

    /**
     * Add puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionPruebaLumboPelvica $puntuacione
     *
     * @return PruebaEstabilidad
     */
    public function addPuntuacione(\LogicBundle\Entity\PuntuacionPruebaLumboPelvica $puntuacione) {
        $puntuacione->setPruebaLumboPelvica($this);
        $this->puntuaciones[] = $puntuacione;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set estabilidadLumboPelvica
     *
     * @param \LogicBundle\Entity\EstabilidadLumboPelvica $estabilidadLumboPelvica
     *
     * @return PruebaEstabilidad
     */
    public function setEstabilidadLumboPelvica(\LogicBundle\Entity\EstabilidadLumboPelvica $estabilidadLumboPelvica = null) {
        $this->estabilidadLumboPelvica = $estabilidadLumboPelvica;

        return $this;
    }

    /**
     * Get estabilidadLumboPelvica
     *
     * @return \LogicBundle\Entity\EstabilidadLumboPelvica
     */
    public function getEstabilidadLumboPelvica() {
        return $this->estabilidadLumboPelvica;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->puntuaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set pruebaLumboPelvica
     *
     * @param \LogicBundle\Entity\PruebaLumboPelvica $pruebaLumboPelvica
     *
     * @return PruebaEstabilidad
     */
    public function setPruebaLumboPelvica(\LogicBundle\Entity\PruebaLumboPelvica $pruebaLumboPelvica = null) {
        $this->pruebaLumboPelvica = $pruebaLumboPelvica;

        return $this;
    }

    /**
     * Get pruebaLumboPelvica
     *
     * @return \LogicBundle\Entity\PruebaLumboPelvica
     */
    public function getPruebaLumboPelvica() {
        return $this->pruebaLumboPelvica;
    }

    /**
     * Remove puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionPruebaLumboPelvica $puntuacione
     */
    public function removePuntuacione(\LogicBundle\Entity\PuntuacionPruebaLumboPelvica $puntuacione) {
        $this->puntuaciones->removeElement($puntuacione);
    }

    /**
     * Get puntuaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuntuaciones() {
        return $this->puntuaciones;
    }

}
