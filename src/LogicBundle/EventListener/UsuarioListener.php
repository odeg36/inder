<?php

namespace LogicBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\User;

class UsuarioListener {

    protected $container;
    protected $template;
    protected $em;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;

        $this->trans = $this->container->get('translator');
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();


        if ($entity instanceof User) {
            $this->setNombreusuario($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $this->em = $args->getEntityManager();
        $unitOfWork = $this->em->getUnitOfWork();

        if ($entity instanceof User) {
            $this->setNombreusuario($entity);

            $changeset = $unitOfWork->getEntityChangeSet($entity);
            if (key_exists("email", $changeset)) {
                try {
                    $this->enviarEmailCorreo($entity, $changeset['email']);
                } catch (\Exception $exc) {
                    
                }
            }

            if (key_exists("numeroIdentificacion", $changeset) || key_exists("tipoIdentificacion", $changeset)) {
                try {
                    $this->enviarEmailNumeroIdentificacion($entity, $changeset['numeroIdentificacion']);
                } catch (\Exception $exc) {
                    
                }
            }
        }
    }

    public function setNombreusuario(User $usuario) {
        if (!$usuario->getTipoIdentificacion()) {
            return true;
        }

        $username = $usuario->getTipoIdentificacion()->getAbreviatura() . $usuario->getNumeroIdentificacion();
        if ($username != $usuario->getUsername()) {
            $usuario->setUsername($username);
            $usuario->setUsernameCanonical($username);
        }
    }

    public function enviarEmailCorreo($entity, $emails) {
        $message = \Swift_Message::newInstance()
                ->setSubject($this->trans->trans('correos.sujeto.cambio'))
                ->setTo($emails)
                ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                ->setBody(
                $this->container->get('templating')->render(
                        'AdminBundle:Email:email.cambio.html.twig', array('entity' => $entity, 'emails' => $emails)
                ), 'text/html'
        );

        $this->container->get('mailer')->send($message);
    }

    public function enviarEmailNumeroIdentificacion($entity, $identificaciones) {
        $message = \Swift_Message::newInstance()
                ->setSubject($this->trans->trans('correos.sujeto.cambio'))
                ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                ->setTo($entity->getEmail())
                ->setBody(
                $this->container->get('templating')->render(
                        'AdminBundle:Email:username.cambio.html.twig', array('entity' => $entity, 'identificaciones' => $identificaciones)
                ), 'text/html'
        );

        $this->container->get('mailer')->send($message);
    }

}
