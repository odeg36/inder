<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Anamnesis
 *
 * @ORM\Table(name="puntuacion_prueba_lumbo_pelvica")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PuntuacionPruebaLumboPelvicaRepository")
 */
class PuntuacionPruebaLumboPelvica {

    const MEDIDA_MINUTOS = "Minutos";
    const MEDIDA_SEGUNDOS = "Segundos";

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
     * @var float
     *
     * @ORM\Column(name="valor", type="float",nullable=true)
     */
    private $valor;

    /**
     * @var string
     *
     * @ORM\Column(name="medida", type="string", length=255, nullable=true)
     */
    private $medida;

    /**
     * @ORM\ManyToOne(targetEntity="PruebaEstabilidad", inversedBy="puntuaciones")
     * @ORM\JoinColumn(name="prueba_lumbo_pelvica_id", referencedColumnName="id")
     */
    private $pruebaLumboPelvica;

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
     * @param float $valor
     *
     * @return PuntuacionPruebaLumboPelvica
     */
    public function setValor($valor) {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor() {
        return $this->valor;
    }

    /**
     * Set medida
     *
     * @param string $medida
     *
     * @return PuntuacionPruebaLumboPelvica
     */
    public function setMedida($medida) {
        $this->medida = $medida;

        return $this;
    }

    /**
     * Get medida
     *
     * @return string
     */
    public function getMedida() {
        return $this->medida;
    }

    /**
     * Set pruebaLumboPelvica
     *
     * @param \LogicBundle\Entity\PruebaEstabilidad $pruebaLumboPelvica
     *
     * @return PuntuacionPruebaLumboPelvica
     */
    public function setPruebaLumboPelvica(\LogicBundle\Entity\PruebaEstabilidad $pruebaLumboPelvica = null) {
        $this->pruebaLumboPelvica = $pruebaLumboPelvica;

        return $this;
    }

    /**
     * Get pruebaLumboPelvica
     *
     * @return \LogicBundle\Entity\PruebaEstabilidad
     */
    public function getPruebaLumboPelvica() {
        return $this->pruebaLumboPelvica;
    }

}
