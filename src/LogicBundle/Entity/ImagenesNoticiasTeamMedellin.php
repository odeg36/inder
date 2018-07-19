<?php

namespace LogicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImagenesNoticiasTeamMedellin
 *
 * @ORM\Table(name="imagenes_noticias_team_medellin")
 * @ORM\Entity(repositoryClass="LogicBundle\Repository\ImagenesNoticiasTeamMedellinRepository")
 */
class ImagenesNoticiasTeamMedellin {

    public function __toString() {
        return $this->imagenNoticia ? $this->imagenNoticia : '';
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload($path, $file) {
        if (null === $file) {
            return;
        }
        
        $filename = $file->getClientOriginalName();

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = uniqid(date('YmdHis')) . '.' . $ext;
        $file->move(
                $path, $filename
        );
        
        return $filename;
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
     * @ORM\Column(name="imagenNoticia", type="string", length=255)
     */
    private $imagenNoticia;

    /**
     * @ORM\ManyToOne(targetEntity="LogicBundle\Entity\NoticiaTeamMedellin", inversedBy="imagenesNoticiasTeamMedellin")
     * @ORM\JoinColumn(name="noticiaTeamMedellin_id", referencedColumnName="id", )
     */
    private $noticiaTeamMedellin;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function getImagenNoticia() {
        if (!$this->imagenNoticia) {
            $this->imagenNoticia = "img-perfil.png";
        }
        return $this->imagenNoticia;
    }

    /**
     * Set imagenNoticia.
     *
     * @param string $imagenNoticia
     *
     * @return ImagenesNoticiasTeamMedellin
     */
    public function setImagenNoticia($imagenNoticia) {
        $this->imagenNoticia = $imagenNoticia;

        return $this;
    }

    /**
     * Set noticiaTeamMedellin
     *
     * @param \LogicBundle\Entity\NoticiaTeamMedellin $noticiaTeamMedellin
     *
     * @return ImagenesNoticiasTeamMedellin
     */
    public function setNoticiaTeamMedellin(\LogicBundle\Entity\NoticiaTeamMedellin $noticiaTeamMedellin = null) {
        $this->noticiaTeamMedellin = $noticiaTeamMedellin;

        return $this;
    }

    /**
     * Get noticiaTeamMedellin
     *
     * @return \LogicBundle\Entity\NoticiaTeamMedellin
     */
    public function getNoticiaTeamMedellin() {
        return $this->noticiaTeamMedellin;
    }

}
