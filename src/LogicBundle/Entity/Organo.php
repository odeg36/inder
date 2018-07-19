<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Organo
 *
 * @ORM\Table(name="organo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OrganoRepository")
 */
class Organo {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TipoOrganismo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $tipoOrgano;

    /**
     * @ORM\ManyToOne(targetEntity="OrganizacionDeportiva", inversedBy="organos")
     * @ORM\JoinColumn(name="organizacion_deportiva_id", referencedColumnName="id")
     */
    private $organizacionDeportiva;

    /**
     * @ORM\OneToMany(targetEntity="PerfilOrganismo", mappedBy="organo", cascade={"persist", "remove"})
     */
    protected $perfilOrganismos;

    /**
     * Add perfilOrganismo
     *
     * @param \LogicBundle\Entity\PerfilOrganismo $perfilOrganismo
     *
     * @return Organo
     */
    public function addPerfilOrganismo(\LogicBundle\Entity\PerfilOrganismo $perfilOrganismo) {
        $perfilOrganismo->setOrgano($this);
        $this->perfilOrganismos[] = $perfilOrganismo;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->perfilOrganismos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set tipoOrgano
     *
     * @param \LogicBundle\Entity\TipoOrganismo $tipoOrgano
     *
     * @return Organo
     */
    public function setTipoOrgano(\LogicBundle\Entity\TipoOrganismo $tipoOrgano = null) {
        $this->tipoOrgano = $tipoOrgano;

        return $this;
    }

    /**
     * Get tipoOrgano
     *
     * @return \LogicBundle\Entity\TipoOrganismo
     */
    public function getTipoOrgano() {
        return $this->tipoOrgano;
    }

    /**
     * Set organizacionDeportiva
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva
     *
     * @return Organo
     */
    public function setOrganizacionDeportiva(\LogicBundle\Entity\OrganizacionDeportiva $organizacionDeportiva = null) {
        $this->organizacionDeportiva = $organizacionDeportiva;

        return $this;
    }

    /**
     * Get organizacionDeportiva
     *
     * @return \LogicBundle\Entity\OrganizacionDeportiva
     */
    public function getOrganizacionDeportiva() {
        return $this->organizacionDeportiva;
    }

    /**
     * Remove perfilOrganismo
     *
     * @param \LogicBundle\Entity\PerfilOrganismo $perfilOrganismo
     */
    public function removePerfilOrganismo(\LogicBundle\Entity\PerfilOrganismo $perfilOrganismo) {
        $this->perfilOrganismos->removeElement($perfilOrganismo);
    }

    /**
     * Get perfilOrganismos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfilOrganismos() {
        return $this->perfilOrganismos;
    }

}
