<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsUsuarioUnicoValidator extends ConstraintValidator {

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
        if (!$this->object->getTipoIdentificacion()) {
            return false;
        }

        $tipoIdentificacion = $this->object->getTipoIdentificacion()->getAbreviatura();
        $numeroIdentificacion = $this->object->getNumeroIdentificacion();

        $username = $tipoIdentificacion . $numeroIdentificacion;
        $id = $this->object->getId();
        $usuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->buscarUsername($username, $id);

        if ($usuario) {
            $this->context->buildViolation($constraint->message)
                    ->addViolation();

            return true;
        }
    }

}
