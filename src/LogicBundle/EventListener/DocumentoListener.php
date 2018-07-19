<?php

namespace LogicBundle\EventListener;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use LogicBundle\Entity\ArchivoEscenario;
use LogicBundle\Entity\Banner;
use LogicBundle\Entity\CarneEvento;
use LogicBundle\Entity\EscenarioDeportivo;
use LogicBundle\Entity\Evento;
use LogicBundle\Entity\InformacionExtraUsuario;
use LogicBundle\Entity\JugadorEvento;
use LogicBundle\Entity\Noticia;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\Recomendado;
use LogicBundle\Entity\TemaModelo;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DocumentoListener {

    protected $path;
    protected $container;

    public function __construct(ContainerInterface $container, $path) {
        $this->container = $container;
        $this->path = $path;
    }

    public function prePersist(LifecycleEventArgs $args) {

        $entity = $args->getEntity();

        if ($entity instanceof User) {
            if (!is_string($entity->getImagenPerfil())) {
                $filename = $entity->upload($this->path, $entity->getImagenPerfil());
                $entity->setImagenPerfil($filename);
            }
        }
        if ($entity instanceof CarneEvento) {
            if (!is_string($entity->getFile())) {
                $filename = $entity->upload($this->path, $entity->getFile());
                $entity->setFile($filename);
            }
        }
        if ($entity instanceof Oferta) {
            if (!is_string($entity->getImagen())) {
                $filename = $entity->upload($this->path, $entity->getImagen());
                $entity->setImagen($filename);
            }
        }

        if ($entity instanceof Banner) {
            if (!is_string($entity->getImagenWeb())) {
                $filename = $entity->upload($this->path, $entity->getImagenWeb());
                $protocolo = "http://";
                $servidor = $_SERVER["HTTP_HOST"];
                $filename = $protocolo . $servidor . "/uploads/" . $filename;
                $entity->setImagenWeb($filename);
            }
            if (!is_string($entity->getImagenMobil())) {
                $filename = $entity->upload($this->path, $entity->getImagenMobil());
                $protocolo = "http://";
                $servidor = $_SERVER["HTTP_HOST"];
                $filename = $protocolo . $servidor . "/uploads/" . $filename;
                $entity->setImagenMobil($filename);
            }
        }

        if ($entity instanceof EscenarioDeportivo) {
            if (!is_string($entity->getImagenEscenarioDividido())) {
                $filename = $entity->upload($this->path, $entity->getImagenEscenarioDividido());
                $entity->setImagenEscenarioDividido($filename);
            }
        }

        if ($entity instanceof TemaModelo) {
            if (!is_string($entity->getImagen())) {
                $filename = $entity->upload($this->path, $entity->getImagen());
                $entity->setImagen($filename);
            }
        }

        if ($entity instanceof ArchivoEscenario) {
            if (!is_string($entity->getFile())) {
                $filename = $entity->upload($this->path, $entity->getFile());
                $entity->setFile($filename);
            }
        }

        if ($entity instanceof Evento) {
            if (!is_string($entity->getImagen())) {
                $filename = $entity->upload($this->path, $entity->getImagen());
                $entity->setImagen($filename);
            }
        }

        if ($entity instanceof Noticia) {
            if (!is_string($entity->getNoticiaImagen())) {
                $filename = $entity->upload($this->path, $entity->getNoticiaImagen());
                $entity->setNoticiaImagen($filename);
            }
        }

        if ($entity instanceof JugadorEvento) {
            if (!is_string($entity->getEpsImagen())) {
                $filename = $entity->upload($this->path, $entity->getEpsImagen());
                $entity->setEpsImagen($filename);
            }
            if (!is_string($entity->getDocumentoImagen())) {
                $filename = $entity->upload($this->path, $entity->getDocumentoImagen());
                $entity->setDocumentoImagen($filename);
            }
        }

        if ($entity instanceof InformacionExtraUsuario) {
            if (!is_string($entity->getAdjuntarDocumentos())) {
                $filename = $entity->upload($this->path, $entity->getAdjuntarDocumentos());
                $entity->setAdjuntarDocumentos($filename);
            }
        }

        if ($entity instanceof Recomendado) {
            if (!is_string($entity->getImagenUrl())) {
                $filename = $entity->upload($this->path, $entity->getImagenUrl());
                $entity->setImagenUrl($filename);
            }
        }
        if ($entity instanceof InformacionExtraUsuario) {
            if (!is_string($entity->getAdjuntarDocumentos())) {
                $filename = $entity->upload($this->path, $entity->getAdjuntarDocumentos());
                $entity->setAdjuntarDocumentos($filename);
            }
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            if (!is_string($entity->getImagenPerfil())) {
                $filename = $entity->upload($this->path, $entity->getImagenPerfil());
                $entity->setImagenPerfil($filename);
            }
        }
        if ($entity instanceof Oferta) {
            if (!is_string($entity->getImagen())) {
                $filename = $entity->upload($this->path, $entity->getImagen());
                $entity->setImagen($filename);
            }
        }
        if ($entity instanceof CarneEvento) {
            if (!is_string($entity->getFile()) && $entity->getFile()) {
                $filename = $entity->upload($this->path, $entity->getFile());
                $entity->setFile($filename);
            }
        }

        if ($entity instanceof Banner) {
            if (!is_string($entity->getImagenWeb())) {
                $filename = $entity->upload($this->path, $entity->getImagenWeb());
                $protocolo = "http://";
                $servidor = $_SERVER["HTTP_HOST"];
                $filename = $protocolo . $servidor . "/uploads/" . $filename;
                $entity->setImagenWeb($filename);
            }
            if (!is_string($entity->getImagenMobil())) {
                $filename = $entity->upload($this->path, $entity->getImagenMobil());
                $protocolo = "http://";
                $servidor = $_SERVER["HTTP_HOST"];
                $filename = $protocolo . $servidor . "/uploads/" . $filename;
                $entity->setImagenMobil($filename);
            }
        }

        if ($entity instanceof EscenarioDeportivo) {
            if (!is_string($entity->getImagenEscenarioDividido())) {
                $filename = $entity->upload($this->path, $entity->getImagenEscenarioDividido());
                $entity->setImagenEscenarioDividido($filename);
            }
        }

        if ($entity instanceof ArchivoEscenario) {
            if (!is_string($entity->getFile())) {
                $filename = $entity->upload($this->path, $entity->getFile());
                $entity->setFile($filename);
            }
        }

        if ($entity instanceof Evento) {
            if (!is_string($entity->getImagen())) {
                $filename = $entity->upload($this->path, $entity->getImagen());
                $entity->setImagen($filename);
            }
        }

        if ($entity instanceof Noticia) {
            if (!is_string($entity->getNoticiaImagen())) {
                $filename = $entity->upload($this->path, $entity->getNoticiaImagen());
                $entity->setNoticiaImagen($filename);
            }
        }

        if ($entity instanceof InformacionExtraUsuario) {
            if (!is_string($entity->getAdjuntarDocumentos())) {
                $filename = $entity->upload($this->path, $entity->getAdjuntarDocumentos());
                $entity->setAdjuntarDocumentos($filename);
            }
            if (!is_string($entity->getLicenciaCiclismo())) {
                $filename = $entity->upload($this->path, $entity->getLicenciaCiclismo());
                $entity->setLicenciaCiclismo($filename);
            }
        }

        if ($entity instanceof JugadorEvento) {
            if (!is_string($entity->getDocumentoImagen())) {
                $filename = $entity->upload($this->path, $entity->getDocumentoImagen());
                $entity->setDocumentoImagen($filename);
            }
            if (!is_string($entity->getEpsImagen())) {
                $filename = $entity->upload($this->path, $entity->getEpsImagen());
                $entity->setEpsImagen($filename);
            }
        }

        if ($entity instanceof InformacionExtraUsuario) {
            if (!is_string($entity->getAdjuntarDocumentos())) {
                $filename = $entity->upload($this->path, $entity->getAdjuntarDocumentos());
                $entity->setAdjuntarDocumentos($filename);
            }
        }

        if ($entity instanceof Recomendado) {
            if (!is_string($entity->getImagenUrl())) {
                $filename = $entity->upload($this->path, $entity->getImagenUrl());
                $entity->setImagenUrl($filename);
            }
        }
        if ($entity instanceof InformacionExtraUsuario) {
            if (!is_string($entity->getAdjuntarDocumentos())) {
                $filename = $entity->upload($this->path, $entity->getAdjuntarDocumentos());
                $entity->setAdjuntarDocumentos($filename);
            }
        }
    }
    
    

}
