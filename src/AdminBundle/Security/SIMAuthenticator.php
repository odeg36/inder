<?php

namespace AdminBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class SIMAuthenticator implements SimpleFormAuthenticatorInterface {

    private $encoder;
    private $container;

    public function __construct(UserPasswordEncoderInterface $encoder, \Symfony\Component\DependencyInjection\Container $container) {
        $this->encoder = $encoder;
        $this->container = $container;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
        /** @var RequestStack */
        $requests = $this->container->get('request_stack');
        $request = $requests->getCurrentRequest();
        $tipoDocumento = $request->get('_tipo_documento');
        $documento = $request->get('_documento');
        $em = $this->container->get('doctrine')->getManager();
        $user = $em->getRepository('ApplicationSonataUserBundle:User')->findOneByUsername($tipoDocumento . $documento);
        if (!$user) {
            throw new CustomUserMessageAuthenticationException($this->container->get('translator')->trans('error.usuario_o_clave'));
        }

        if ($user->getFechaExpiracion()) {
            $fechActual = new \DateTime();
            if ($user->getFechaExpiracion() < $fechActual) {
                throw new CustomUserMessageAuthenticationException($this->container->get('translator')->trans('error.usuario.expirado'));
            }
        }
        if (!$user->getEnabled()) {
            throw new CustomUserMessageAuthenticationException($this->container->get('translator')->trans('error.usuario.deshabilitado'));
        }

        $encoder = $this->container->get('inder.encoder');
        $passwordValid = $encoder->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt());
        if ($passwordValid) {
            return new UsernamePasswordToken(
                    $user, $user->getPassword(), $providerKey, $user->getRoles()
            );
        }

        // CAUTION: this message will be returned to the client
        // (so don't put any un-trusted messages / error strings here)
        throw new CustomUserMessageAuthenticationException('error.usuario_incorrecto');
    }

    public function supportsToken(TokenInterface $token, $providerKey) {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey) {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

}
