<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AdminBundle\Controller\ReservaAdminController;

class CronTasksDefaultCommand extends ContainerAwareCommand {

    protected $inasistencias = 2;

    protected function configure() {

        $this->setName('crontasks:EliminarUsuarioOferta')->setDescription('Creates the commands by default in database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $trans = $this->getContainer()->get("translator");


        $ofertas = $em->getRepository('LogicBundle:Oferta')->createQueryBuilder('oferta')
                        ->where('oferta.id != :idOferta')
                        ->setParameter('idOferta', 0)
                        ->getQuery()->getResult();

        foreach ($ofertas as $oferta) {

            foreach ($oferta->getPreinscritos() as $preinscripcion) {
                $numeroInasistencias = 0;
                $asistencia = $em->getRepository('LogicBundle:Asistencia')->findBy(array('oferta' => $oferta->getId(), 'usuario' => $preinscripcion->getUsuario()->getId()));

                foreach ($asistencia as $asis) {
                    if ($asis->getAsistio() == false) {

                        $numeroInasistencias += 1;
                    }
                }

                if ($numeroInasistencias >= $this->inasistencias) {


                    //si envia correo poner aca
                    // validar si se debe eliminar las asistencias de ese usuario tambien
                    $oferta->removePreinscrito($preinscripcion);
                    $em->remove($preinscripcion);
                    $em->flush();

                    $mailsGestores = array();
                    array_push($mailsGestores, 'juan.benavides@ito-software.com');
                    array_push($mailsGestores, 'juandaprom2013@gmail.com');

                    if ($preinscripcion->getUsuario()->getEmail() != null) {
                        array_push($mailsGestores, $preinscripcion->getUsuario()->getEmail());
                    }

                    $mensaje = $trans->trans('correos.oferta.mensaje', array(
                        '%usuario%' => $preinscripcion->getUsuario()->getFullName(),
                        '%noIdentificacion%' => $preinscripcion->getUsuario()->getNumeroIdentificacion(),
                        '%oferta%' => $oferta->getNombre(),
                        '%cantidadMaximaInasistencias%' => $this->inasistencias
                            )
                    );

                    if (count($mailsGestores) > 0) {
                        $informacion = array('objeto' => $oferta, 'usuario' => $preinscripcion->getUsuario(), 'mensaje' => $mensaje, 'plantilla' => 'AdminBundle:Oferta:mails/mailExpulsarUsuarioOferta.html.twig');
                        $this->enviarCorreoExpulsarUsuarioOferta($mailsGestores, $trans->trans('correos.oferta.asunto'), $informacion, $this->getContainer());
                    }
                }
            }


            $em->persist($oferta);
            $em->flush();
        }
    }

    public function enviarCorreoExpulsarUsuarioOferta($destinatarios, $asunto, $informacion, $container) {
        if (is_array($destinatarios)) {
            foreach ($destinatarios as $destinatario) {
                $message = (new Swift_Message($asunto))
                        ->setTo($destinatario)
                        ->setFrom($container->getParameter('mailer_from_email'), $container->getParameter('mailer_from_name'))
                        ->setBody(
                        $this->renderViewExpulsarUsuarioOferta($informacion['plantilla'], $informacion, $container), 'text/html');
                try {
                    $this->getContainer()->get('mailer')->send($message);
                } catch (Exception $ex) {
                    
                }
            }
        } else {
            $message = (new Swift_Message($asunto))
                    ->setTo($destinatarios)
                    ->setFrom($container->getParameter('mailer_from_email'), $container->getParameter('mailer_from_name'))
                    ->setBody(
                    $this->renderViewExpulsarUsuarioOferta($informacion['plantilla'], $informacion, $container), 'text/html');
            try {
                $this->getContainer()->get('mailer')->send($message);
            } catch (Exception $ex) {
                
            }
        }
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    protected function renderViewExpulsarUsuarioOferta($view, array $parameters = array(), $container) {
        if ($container->has('templating')) {
            return $container->get('templating')->render($view, $parameters);
        }

        if (!$container->has('twig')) {
            throw new \LogicException('You can not use the "renderView" method if the Templating Component or the Twig Bundle are not available.');
        }

        return $container->get('twig')->render($view, $parameters);
    }

}
