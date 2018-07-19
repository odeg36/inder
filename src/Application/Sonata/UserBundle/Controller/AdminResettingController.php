<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use Exception;
use FOS\UserBundle\Model\UserInterface;
use ServicesBundle\Form\SolicitudContrasenaFormType;
use Sonata\UserBundle\Controller\AdminResettingController as BaseController;
use Swift_Message;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AdminResettingController extends BaseController {

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return Response
     */
    public function resetAction(Request $request, $token) {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->get('router')->generate('sonata_admin_dashboard'));
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManagerInterface */
        $loginManager = $this->get('fos_user.security.login_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        $firewallName = $this->container->getParameter('fos_user.firewall_name');

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->generateUrl('sonata_user_admin_resetting_request'));
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $user->setEnabled(true);

            $message = $this->get('translator')->trans('resetting.flash.success', [], 'FOSUserBundle');
            $this->addFlash('success', $message);
            $response = new RedirectResponse($this->generateUrl('sonata_admin_dashboard'));

            try {
                $loginManager->logInUser($firewallName, $user, $response);
                $user->setLastLogin(new \DateTime());
            } catch (AccountStatusException $ex) {
                // We simply do not authenticate users which do not pass the user
                // checker (not enabled, expired, etc.).
                if ($this->has('logger')) {
                    $this->get('logger')->warning(sprintf(
                                    'Unable to login user %d after password reset', $user->getId())
                    );
                }
            }
            $em = $this->container->get('doctrine')->getManager();
            $encoder = $this->container->get('inder.encoder');
            $password = $encoder->encodePassword($form->get('plainPassword')->getData(), $user->getSalt());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            return $response;
        }

        return $this->render('SonataUserBundle:Admin:Security/Resetting/reset.html.twig', [
                    'token' => $token,
                    'form' => $form->createView(),
                    'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                    'admin_pool' => $this->get('sonata.admin.pool'),
        ]);
    }

    /**
     * @return Response
     */
    public function requestAction() {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->get('router')->generate('sonata_admin_dashboard'));
        }

        $form = $this->createForm(SolicitudContrasenaFormType::class);


        return $this->render('SonataUserBundle:Admin:Security/Resetting/request.html.twig', array(
                    'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                    'admin_pool' => $this->get('sonata.admin.pool'), 'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendEmailAction(Request $request) {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->get('router')->generate('sonata_admin_dashboard'));
        }
        $form = $this->createForm(SolicitudContrasenaFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $username = $data['tipo_identificacion']->getAbreviatura() . $data['identificacion'];

            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
//            $user = $userManager->findUserByUsernameOrEmail($username);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('username' => $username));

            $ttl = $this->container->getParameter('fos_user.resetting.retry_ttl');
            if (null !== $user && !$user->isPasswordRequestNonExpired($ttl)) {
                if (!$user->isAccountNonLocked()) {
                    return new RedirectResponse($this->get('router')->generate('sonata_user_admin_resetting_request'));
                }
                
                if(!$user->isAccountNonEnabled()){
                    return new RedirectResponse($this->get('router')->generate('sonata_user_admin_resetting_request', [
                        'bloqueado' => true
                    ]));
                }
                
                if(!$user->isAccountNonExpired()){
                    return new RedirectResponse($this->get('router')->generate('sonata_user_admin_resetting_request', [
                        'expirado' => true
                    ]));
                }
                
                if (null === $user->getConfirmationToken()) {
                    /** @var $tokenGenerator TokenGeneratorInterface */
                    $tokenGenerator = $this->get('fos_user.util.token_generator');
                    $user->setConfirmationToken($tokenGenerator->generateToken());
                }
                $this->sendResettingEmailMessage($user);
                $user->setPasswordRequestedAt(new \DateTime());
                $userManager->updateUser($user);
            }

            return new RedirectResponse($this->generateUrl('sonata_user_admin_resetting_check_email', array(
                        'email' => $user->getEmail(),
            )));
        }
        return $this->render('SonataUserBundle:Admin:Security/Resetting/request.html.twig', array(
                    'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                    'admin_pool' => $this->get('sonata.admin.pool'), 'form' => $form->createView()
        ));
    }

    /**
     * Send an email to a user to confirm the password reset.
     *
     * @param UserInterface $user
     */
    private function sendResettingEmailMessage(UserInterface $user) {
        $url = $this->generateUrl('sonata_user_admin_resetting_reset', array(
            'token' => $user->getConfirmationToken(),
                ), UrlGeneratorInterface::ABSOLUTE_URL);

        // Render the email, use the first line as the subject, and the rest as the body
        $subject = $this->get('translator')->trans('resetting.email.subject', ['%username%' => $user->getFullname()], 'FOSUserBundle');
        
        $message = (new Swift_Message())
                ->setSubject($subject)
                ->setFrom($this->container->getParameter('mailer_from_email'), $this->container->getParameter('mailer_from_name'))
                ->setTo((string) $user->getEmail())
                ->setBody($this->renderView('AdminBundle:Email:password_resetting.html.twig', array(
                            'user' => $user,
                            'confirmationUrl' => $url,
                                ), 'text/html'))
                ->setContentType("text/html");
        try {
            $this->get('mailer')->send($message);
        } catch (\Swift_SwiftException $e) {
        } catch (\Exception $e) {
        }
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function checkEmailAction(Request $request) {
        $email = $request->query->get('email');

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('sonata_user_admin_resetting_request'));
        }

        return $this->render('SonataUserBundle:Admin:Security/Resetting/checkEmail.html.twig', array(
                    'base_template' => $this->get('sonata.admin.pool')->getTemplate('layout'),
                    'admin_pool' => $this->get('sonata.admin.pool'),
                    'email' => $this->ofuscarEmail($email),
                    'tokenLifetime' => ceil($this->container->getParameter('fos_user.resetting.retry_ttl') / 3600),
        ));
    }

    private function ofuscarEmail($email) {
        $em = explode("@", $email);
        $name = implode(array_slice($em, 0, count($em) - 1), '@');
        $len = floor(strlen($name) / 2);

        return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

}
