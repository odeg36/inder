<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResponsableTareaActividad
 *
 * @ORM\Table(name="responsable_tarea_actividad")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ResponsableTareaActividadRepository")
 */
class ResponsableTareaActividad
{
    public function __toString() {
        return $this->getNombre() ? : '';
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="TareaActividad", mappedBy="responsableTareaActividad")
     */
    private $tareaActividades;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tareaActividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return ResponsableTareaActividad
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add tareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $tareaActividade
     *
     * @return ResponsableTareaActividad
     */
    public function addTareaActividade(\LogicBundle\Entity\TareaActividad $tareaActividade)
    {
        $this->tareaActividades[] = $tareaActividade;

        return $this;
    }

    /**
     * Remove tareaActividade.
     *
     * @param \LogicBundle\Entity\TareaActividad $tareaActividade
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTareaActividade(\LogicBundle\Entity\TareaActividad $tareaActividade)
    {
        return $this->tareaActividades->removeElement($tareaActividade);
    }

    /**
     * Get tareaActividades.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTareaActividades()
    {
        return $this->tareaActividades;
    }
}
