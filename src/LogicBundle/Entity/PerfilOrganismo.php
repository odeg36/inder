<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganigramaPerfil
 *
 * @ORM\Table(name="perfil_organismo")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\PerfilOrganismoRepository")
 */
class PerfilOrganismo {

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
     * @ORM\Column(name="documento", type="string", length=255)
     */
    private $documento;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=255, nullable=true)
     */
    private $correo;

    /**
     * @ORM\ManyToOne(targetEntity="Organo", inversedBy="perfilOrganismos", cascade={"persist"})
     * @ORM\JoinColumn(name="organo_id", referencedColumnName="id")
     */
    private $organo;

    /**
     * @ORM\ManyToOne(targetEntity="Perfil", inversedBy="perfilOrganismos")
     * @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")
     */
    private $perfil;

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
     * @return PerfilOrganismo
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
     * Set documento
     *
     * @param string $documento
     *
     * @return PerfilOrganismo
     */
    public function setDocumento($documento) {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return string
     */
    public function getDocumento() {
        return $this->documento;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return PerfilOrganismo
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return PerfilOrganismo
     */
    public function setCorreo($correo) {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo() {
        return $this->correo;
    }

    /**
     * Set organo
     *
     * @param \LogicBundle\Entity\Organo $organo
     *
     * @return PerfilOrganismo
     */
    public function setOrgano(\LogicBundle\Entity\Organo $organo = null) {
        $this->organo = $organo;

        return $this;
    }

    /**
     * Get organo
     *
     * @return \LogicBundle\Entity\Organo
     */
    public function getOrgano() {
        return $this->organo;
    }

    /**
     * Set perfil
     *
     * @param \LogicBundle\Entity\Perfil $perfil
     *
     * @return PerfilOrganismo
     */
    public function setPerfil(\LogicBundle\Entity\Perfil $perfil = null) {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return \LogicBundle\Entity\Perfil
     */
    public function getPerfil() {
        return $this->perfil;
    }

}
