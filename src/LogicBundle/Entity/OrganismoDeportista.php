<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrganismoDeportista
 *
 * @ORM\Table(name="organismo_deportista")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\OrganismoDeportistaRepository")
 */
class OrganismoDeportista {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="DisciplinaOrganizacion", inversedBy="deportistas")
     * @ORM\JoinColumn(name="disciplina_id", referencedColumnName="id")
     */
    private $disciplinaOrganizacion;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="deportistas")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuarioDeportista;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set disciplinaOrganizacion
     *
     * @param \LogicBundle\Entity\DisciplinaOrganizacion $disciplinaOrganizacion
     *
     * @return OrganismoDeportista
     */
    public function setDisciplinaOrganizacion(\LogicBundle\Entity\DisciplinaOrganizacion $disciplinaOrganizacion = null) {
        $this->disciplinaOrganizacion = $disciplinaOrganizacion;

        return $this;
    }

    /**
     * Get disciplinaOrganizacion
     *
     * @return \LogicBundle\Entity\DisciplinaOrganizacion
     */
    public function getDisciplinaOrganizacion() {
        return $this->disciplinaOrganizacion;
    }

    /**
     * Set usuarioDeportista
     *
     * @param \Application\Sonata\UserBundle\Entity\User $usuarioDeportista
     *
     * @return OrganismoDeportista
     */
    public function setUsuarioDeportista(\Application\Sonata\UserBundle\Entity\User $usuarioDeportista = null) {
        $this->usuarioDeportista = $usuarioDeportista;

        return $this;
    }

    /**
     * Get usuarioDeportista
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUsuarioDeportista() {
        return $this->usuarioDeportista;
    }

}
