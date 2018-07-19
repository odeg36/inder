<?php

namespace AdminBundle\Service;

use LogicBundle\Utils\Validaciones;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface {

    protected
            $router,
            $security,
            $organizaciondeportiva;

    public function __construct(Router $router, AuthorizationChecker $security) {
        $this->router = $router;
        $this->security = $security;
        $this->validaciones = new Validaciones();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        // URL for redirect the user to where they were before the login process begun if you want.
        // $referer_url = $request->headers->get('referer');
        // Default target for unknown roles. Everyone else go there.
        $url = 'homepage';
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $url = 'sonata_admin_dashboard';
        } else {
            $url = $this->validaciones->urlAccesoOD($token->getUser());
        }
        $response = new RedirectResponse($this->router->generate($url));
        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        // Create a flash message with the authentication error message
        $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);
        $url = $this->router->generate('fos_user_security_login');

        return new RedirectResponse($url);
    }

}
