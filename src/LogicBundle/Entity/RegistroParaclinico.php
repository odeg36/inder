<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RegistroParaclinico
 *
 * @ORM\Table(name="registro_paraclinico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RegistroParaclinicoRepository")
 */
class RegistroParaclinico
{
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
     * @ORM\Column(name="laboratorio", type="string", length=255, nullable=true)
     */
    private $laboratorio;

    /**
     * @var string
     *
     * @ORM\Column(name="resultado", type="string", length=255, nullable=true)
     */
    private $resultado;

    /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="text", nullable=true)
     */
    private $observacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="ConsultaMedico", inversedBy="registrosParaclinicos")
     * @ORM\JoinColumn(name="consulta_medico_id", referencedColumnName="id")
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
     * Set laboratorio
     *
     * @param string $laboratorio
     *
     * @return RegistroParaclinico
     */
    public function setLaboratorio($laboratorio)
    {
        $this->laboratorio = $laboratorio;

        return $this;
    }

    /**
     * Get laboratorio
     *
     * @return string
     */
    public function getLaboratorio()
    {
        return $this->laboratorio;
    }

    /**
     * Set resultado
     *
     * @param string $resultado
     *
     * @return RegistroParaclinico
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return RegistroParaclinico
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return RegistroParaclinico
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
