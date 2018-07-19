<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoOrganismo
 *
 * @ORM\Table(name="tipo_organismo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\TipoOrganismoRepository")
 */
class TipoOrganismo {

    public function __toString() {
        return $this->nombre ? $this->nombre : '';
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
     * @var string
     *
     * @ORM\Column(name="abreviatura", type="string", length=5, nullable=true)
     */
    private $abreviatura;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximo", type="integer", nullable=true)
     */
    private $maximo;

    /**
     * @var integer
     *
     * @ORM\Column(name="minimo", type="integer", nullable=true)
     */
    private $minimo;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="TipoEntidad", inversedBy="tipoOrganismos")
     * @ORM\JoinTable(name="tipo_organismo_entidad",
     *      joinColumns={@ORM\JoinColumn(name="tipo_organismo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tipo_entidad_id", referencedColumnName="id")}
     * )
     */
    private $tipoEntidades;

    /**
     * Constructor
     */
    public function __construct() {
        $this->tipoEntidades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoOrganismo
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
     * Set maximo
     *
     * @param integer $maximo
     *
     * @return TipoOrganismo
     */
    public function setMaximo($maximo) {
        $this->maximo = $maximo;

        return $this;
    }

    /**
     * Get maximo
     *
     * @return integer
     */
    public function getMaximo() {
        return $this->maximo;
    }

    /**
     * Set minimo
     *
     * @param integer $minimo
     *
     * @return TipoOrganismo
     */
    public function setMinimo($minimo) {
        $this->minimo = $minimo;

        return $this;
    }

    /**
     * Get minimo
     *
     * @return integer
     */
    public function getMinimo() {
        return $this->minimo;
    }

    /**
     * Add tipoEntidade
     *
     * @param \LogicBundle\Entity\TipoEntidad $tipoEntidade
     *
     * @return TipoOrganismo
     */
    public function addTipoEntidade(\LogicBundle\Entity\TipoEntidad $tipoEntidade) {
        $this->tipoEntidades[] = $tipoEntidade;

        return $this;
    }

    /**
     * Remove tipoEntidade
     *
     * @param \LogicBundle\Entity\TipoEntidad $tipoEntidade
     */
    public function removeTipoEntidade(\LogicBundle\Entity\TipoEntidad $tipoEntidade) {
        $this->tipoEntidades->removeElement($tipoEntidade);
    }

    /**
     * Get tipoEntidades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoEntidades() {
        return $this->tipoEntidades;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return TipoOrganismo
     */
    public function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura() {
        return $this->abreviatura;
    }

}
