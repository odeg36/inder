<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PruebaSentadillaArranque
 *
 * @ORM\Table(name="prueba_sentadilla_arranque")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PruebaSentadillaArranqueRepository")
 */
class PruebaSentadillaArranque {

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
     * @ORM\ManyToOne(targetEntity="SentadillaArranque", inversedBy="pruebasSentadillaArranque")
     * @ORM\JoinColumn(name="sentadilla_arranque_id", referencedColumnName="id")
     */
    private $sentadillaArranque;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaSentadillaArranque", inversedBy="pruebasSentadillaArranque")
     * @ORM\JoinColumn(name="categoria_sentadilla_arranque_id", referencedColumnName="id")
     */
    private $categoriaSentadillaArranque;

    /**
     * @ORM\OneToMany(targetEntity="PuntuacionSentadillaArranque", mappedBy="pruebaSentadillaArranque", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $puntuaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * Add puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionSentadillaArranque $puntuacione
     *
     * @return PruebaSentadillaArranque
     */
    public function addPuntuacione(\LogicBundle\Entity\PuntuacionSentadillaArranque $puntuacione) {
        $puntuacione->setPruebaSentadillaArranque($this);
        $this->puntuaciones[] = $puntuacione;

        return $this;
    }

    //// ***************    FIN MODIFICACIONES     ************* /////

    /**
     * Constructor
     */
    public function __construct() {
        $this->puntuaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return PruebaSentadillaArranque
     */
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones() {
        return $this->observaciones;
    }

    /**
     * Set sentadillaArranque
     *
     * @param \LogicBundle\Entity\SentadillaArranque $sentadillaArranque
     *
     * @return PruebaSentadillaArranque
     */
    public function setSentadillaArranque(\LogicBundle\Entity\SentadillaArranque $sentadillaArranque = null) {
        $this->sentadillaArranque = $sentadillaArranque;

        return $this;
    }

    /**
     * Get sentadillaArranque
     *
     * @return \LogicBundle\Entity\SentadillaArranque
     */
    public function getSentadillaArranque() {
        return $this->sentadillaArranque;
    }

    /**
     * Set categoriaSentadillaArranque
     *
     * @param \LogicBundle\Entity\CategoriaSentadillaArranque $categoriaSentadillaArranque
     *
     * @return PruebaSentadillaArranque
     */
    public function setCategoriaSentadillaArranque(\LogicBundle\Entity\CategoriaSentadillaArranque $categoriaSentadillaArranque = null) {
        $this->categoriaSentadillaArranque = $categoriaSentadillaArranque;

        return $this;
    }

    /**
     * Get categoriaSentadillaArranque
     *
     * @return \LogicBundle\Entity\CategoriaSentadillaArranque
     */
    public function getCategoriaSentadillaArranque() {
        return $this->categoriaSentadillaArranque;
    }

    /**
     * Remove puntuacione
     *
     * @param \LogicBundle\Entity\PuntuacionSentadillaArranque $puntuacione
     */
    public function removePuntuacione(\LogicBundle\Entity\PuntuacionSentadillaArranque $puntuacione) {
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
