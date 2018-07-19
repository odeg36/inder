<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="puntuacion_sentadilla_arranque")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PuntuacionSentadillaArranqueRepository")
 */
class PuntuacionSentadillaArranque {

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
     * @var boolean
     *
     * @ORM\Column(name="valor", type="boolean")
     */
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="PruebaSentadillaArranque", inversedBy="puntuaciones")
     * @ORM\JoinColumn(name="prueba__sentadilla_arranque_id", referencedColumnName="id")
     */
    private $pruebaSentadillaArranque;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set valor
     *
     * @param boolean $valor
     *
     * @return PuntuacionSentadillaArranque
     */
    public function setValor($valor) {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return boolean
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * Set pruebaSentadillaArranque
     *
     * @param \LogicBundle\Entity\PruebaSentadillaArranque $pruebaSentadillaArranque
     *
     * @return PuntuacionSentadillaArranque
     */
    public function setPruebaSentadillaArranque(\LogicBundle\Entity\PruebaSentadillaArranque $pruebaSentadillaArranque = null) {
        $this->pruebaSentadillaArranque = $pruebaSentadillaArranque;

        return $this;
    }

    /**
     * Get pruebaSentadillaArranque
     *
     * @return \LogicBundle\Entity\PruebaSentadillaArranque
     */
    public function getPruebaSentadillaArranque() {
        return $this->pruebaSentadillaArranque;
    }

}
