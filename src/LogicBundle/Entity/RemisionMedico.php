<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Remision
 *
 * @ORM\Table(name="remision_medico")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\RemisionMedicoRepository")
 */
class RemisionMedico
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
     * @ORM\Column(name="nombre", type="text", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToOne(targetEntity="ConsultaMedico", inversedBy="remision")
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RemisionMedico
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
     * Set consultaMedico
     *
     * @param \LogicBundle\Entity\ConsultaMedico $consultaMedico
     *
     * @return RemisionMedico
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
