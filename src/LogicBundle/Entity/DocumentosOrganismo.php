<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentosOrganismo
 *
 * @ORM\Table(name="documentos_organismo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\DocumentosOrganismoRepository")
 */
class DocumentosOrganismo {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\OrganizacionDeportiva", inversedBy="documentos")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $organizacion;

    /**
     * @var string
     * 
     * @ORM\Column(name="archivo", type="string", length=255)
     */
    private $archivo;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     *
     * @return DocumentosOrganismo
     */
    public function setArchivo($archivo) {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo() {
        return $this->archivo;
    }

    /**
     * Set organizacion
     *
     * @param \LogicBundle\Entity\OrganizacionDeportiva $organizacion
     *
     * @return DocumentosOrganismo
     */
    public function setOrganizacion(\LogicBundle\Entity\OrganizacionDeportiva $organizacion = null) {
        $this->organizacion = $organizacion;
        return $this;
    }

    /**
     * Get organizacion
     *
     * @return \LogicBundle\Entity\OrganizacionDeportiva
     */
    public function getOrganizacion() {
        return $this->organizacion;
    }

}
