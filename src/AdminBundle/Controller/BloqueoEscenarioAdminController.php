<?php

namespace AdminBundle\Controller;

use LogicBundle\Entity\BloqueoEscenario;
use LogicBundle\Entity\DivisionBloqueo;
use LogicBundle\Entity\Oferta;
use LogicBundle\Entity\Reserva;
use ReflectionClass;
use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormView;
use Twig_Error_Runtime;

class BloqueoEscenarioAdminController extends CRUDController {

    protected $em = null;
    protected $trans = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);

        $this->trans = $container->get("translator");
        $this->em = $container->get("doctrine")->getManager();
    }

    public function createAction() {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->renderWithExtraParams(
                            '@SonataAdmin/CRUD/select_subclass.html.twig', [
                        'base_template' => $this->getBaseTemplate(),
                        'admin' => $this->admin,
                        'action' => 'create',
                            ], null
            );
        }

        $newObject = $this->admin->getNewInstance();

        $preResponse = $this->preCreate($request, $newObject);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($newObject);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($newObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO: remove this check for 4.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($newObject);
            }
            if (count($form->get("division")->getData()) == 0) {
                $form->get("division")->addError(new FormError($this->trans->trans('novacio')));
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $submittedObject = $form->getData();
                foreach ($form->get("division")->getData() as $div) {
                    $division = $this->em->getRepository("LogicBundle:Division")->findOneById($div);
                    $divisionBLoqueo = new DivisionBloqueo($division, $submittedObject);
                    $submittedObject->addDivisionesBLoqueo($divisionBLoqueo);
                }
                $this->admin->setSubject($submittedObject);
                $this->admin->checkAccess('create', $submittedObject);

                try {
                    $this->BuscarReservasEventos($submittedObject);
                    $submittedObject->setHoraInicial(new \Datetime($submittedObject->getHoraInicial()));
                    $submittedObject->setHoraFinal(new \Datetime($submittedObject->getHoraFinal()));
                    $newObject = $this->admin->create($submittedObject);
                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson([
                                    'result' => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($newObject),
                                        ], 200, []);
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->trans(
                                    'flash_create_success', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($newObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->trans(
                                    'flash_create_error', ['%name%' => $this->escapeHtml($this->admin->toString($newObject))], 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams($this->admin->getTemplate($templateKey), [
                    'action' => 'create',
                    'form' => $formView,
                    'object' => $newObject,
                    'objectId' => null,
                        ], null);
    }

    private function setFormTheme(FormView $formView, $theme) {
        $twig = $this->get('twig');

        try {
            $twig
                    ->getRuntime('Symfony\Bridge\Twig\Form\TwigRenderer')
                    ->setTheme($formView, $theme);
        } catch (Twig_Error_Runtime $e) {
            // BC for Symfony < 3.2 where this runtime not exists
            $twig
                    ->getExtension('Symfony\Bridge\Twig\Extension\FormExtension')
                    ->renderer
                    ->setTheme($formView, $theme);
        }
    }

    public function buscarReservasEventos(BloqueoEscenario $bloqueoEscenario) {
        $ofertas = $this->em->getRepository("LogicBundle:Oferta")->buscarOfertasBloqueo($bloqueoEscenario);
        if ($ofertas) {
            foreach ($ofertas as $oferta) {
                //enviar correo a formador, gestor y participantes
                $oferta->setActivo(false);
                $this->em->persist($oferta);
                $correos = [];
                foreach ($oferta->getPreinscritos() as $preinscrito) {
                    if ($preinscrito->getUsuario()->getEmail()) {
                        $correos[] = $preinscrito->getUsuario()->getEmail();
                    }
                }
                if ($oferta->getFormador()->getEmail()) {
                    $correos[] = $oferta->getFormador()->getEmail();
                }
                if ($oferta->getGestor()->getEmail()) {
                    $correos[] = $oferta->getGestor()->getEmail();
                }
                if (count($correos) > 0) {
                    $this->enviarCorreos($oferta, null, $bloqueoEscenario, $correos);
                }

                $preinscritos = $oferta->getPreinscritos();
            }
        }
        $reservas = $this->em->getRepository("LogicBundle:Reserva")->buscarReservasBloqueo($bloqueoEscenario);
        if ($reservas) {
            $correos = [];
            foreach ($reservas as $reserva) {
                if ($reserva->getUsuario()->getEmail()) {
                    $correos[] = $reserva->getUsuario()->getEmail();
                }
                foreach ($reserva->getDivisiones() as $divisionReserva) {
                    foreach ($divisionReserva->getDivisionReservas() as $usuarioDivisionReserva) {
                        if ($usuarioDivisionReserva->getUsuario()->getEmail()) {
                            $correos[] = $usuarioDivisionReserva->getUsuario()->getEmail();
                        }
                    }
                }
                if (count($correos) > 0) {
                    $this->enviarCorreos(null, $reserva, $bloqueoEscenario, $correos);
                }
            }
        }
    }

    public function enviarCorreos(Oferta $oferta = null, Reserva $reserva = null, BloqueoEscenario $bloqueo, $correos) {
        if ($oferta) {
            $informacion = array('oferta' => $oferta, 'bloqueoEscenario' => $bloqueo, 'plantilla' => 'AdminBundle:BloqueoEscenario:Mail/cancelacio_oferta.html.twig');
            $message = (new Swift_Message("INDER SIMON - Cancelación oferta"))
                    ->setTo($correos)
                    ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                    ->setBody(
                    $this->renderView($informacion['plantilla'], $informacion), 'text/html');
            try {
                $this->get('mailer')->send($message);
            } catch (Exception $ex) {
                return $ex;
            }
        }
        if ($reserva) {
            $informacion = array('reserva' => $reserva, 'bloqueoEscenario' => $bloqueo, 'plantilla' => 'AdminBundle:BloqueoEscenario:Mail/cancelacio_reserva.html.twig');
            $message = (new Swift_Message("INDER SIMON - Cancelación reserva"))
                    ->setTo($correos)
                    ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                    ->setBody(
                    $this->renderView($informacion['plantilla'], $informacion), 'text/html');
            try {
                $this->get('mailer')->send($message);
            } catch (Exception $ex) {
                return $ex;
            }
        }
    }

}
