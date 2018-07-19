<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContrareferenciaRecomendacion
 *
 * @ORM\Table(name="contrareferencia_recomendacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ContrareferenciaRecomendacionRepository")
 */
class ContrareferenciaRecomendacion {

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
     * @ORM\Column(name="contrareferencia", type="text")
     */
    private $contrareferencia;

    /**
     * @var string
     *
     * @ORM\Column(name="recomendacion", type="text")
     */
    private $recomendacion;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaMedico", inversedBy="contrareferenciaRecomendacion")
     */
    private $consultaMedico;

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
     * Set contrareferencia
     *
     * @param string $contrareferencia
     *
     * @return ContrareferenciaRecomendacion
     */
    public function setContrareferencia($contrareferencia)
    {
        $this->contrareferencia = $contrareferencia;

        return $this;
    }

    /**
     * Get contrareferencia
     *
     * @return string
     */
    public function getContrareferencia()
    {
        return $this->contrareferencia;
    }

    /**
     * Set recomendacion
     *
     * @param string $recomendacion
     *
     * @return ContrareferenciaRecomendacion
     */
    public function setRecomendacion($recomendacion)
    {
        $this->recomendacion = $recomendacion;

        return $this;
    }

    /**
     * Get recomendacion
     *
     * @return string
     */
    public function getRecomendacion()
    {
        return $this->recomendacion;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return ContrareferenciaRecomendacion
     */
    public function setConsultaMedico(\LogicBundle\Entity\ConsultaMedico $consultaMedico = null)
    {
        $this->consultaMedico = $consultaMedico;

        return $this;
    }

    /**
     * Get consultaMedico
     *
     * @return \LogicBundle\Entity\ConsultaMedico
     */
    public function getConsultaMedico()
    {
        return $this->consultaMedico;
    }
}
