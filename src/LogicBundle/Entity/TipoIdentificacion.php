<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * TipoIdentificacion
 *
 * @ORM\Table(name="tipo_identificacion")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoIdentificacionRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class TipoIdentificacion {

    const CC = "CC"; // Cédula de Ciudadanía
    const CE = "CE"; // Cédula de Extranjería
    const NT = "NT"; // NIT
    const PS = "PS"; // Pasaporte
    const RC = "RC"; // Registro Civil
    const NU = "NU"; // Numero unico de identificacion personal
    const TI = "TI"; // Tarjeta de Identidad
    
    public function __toString() {
        return $this->nombre ? $this->nombre : '';
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=255)
     * @Serializer\Expose
     */
    private $abreviatura;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Serializer\Expose
     */
    private $nombre;

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
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return TipoIdentificacion
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TipoIdentificacion
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
}
