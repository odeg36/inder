<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsUsuarioConEmailValidator extends ConstraintValidator {

    protected $object;
    protected $em;
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    public function initialize(\Symfony\Component\Validator\Context\ExecutionContextInterface $context) {
        parent::initialize($context);

        $this->object = $context->getRoot()->getData();
    }

    public function validate($value, Constraint $constraint) {
        if (!$value) {
            return true;
        }

        $this->buscarUsuario($constraint);
    }

    public function buscarUsuario(Constraint $constraint) {
        if (!$this->object['tipo_identificacion']) {
            return false;
        }

        $tipoIdentificacion = $this->object['tipo_identificacion']->getAbreviatura();
        $numeroIdentificacion = $this->object['identificacion'];

        $username = $tipoIdentificacion . $numeroIdentificacion;
        $usuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneByUsername($username);
        if (!$usuario) {
            $this->context->buildViolation($constraint->message_no_existe)
                    ->addViolation();

            return true;
        }
        if (!$usuario->getEmail()) {
            $this->context->buildViolation($constraint->message_sin_email)
                    ->addViolation();

            return true;
        }
    }

}
