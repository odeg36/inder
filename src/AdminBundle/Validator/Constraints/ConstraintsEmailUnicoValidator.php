<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsEmailUnicoValidator extends ConstraintValidator {

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

        $this->buscarEmail($constraint);
    }

    public function buscarEmail(Constraint $constraint) {
        if (!$this->object->getTipoIdentificacion()) {
            return false;
        }

        $email = $this->object->getEmail();
        $usuario = $this->em->getRepository("ApplicationSonataUserBundle:User")->findOneByEmail($email);

        if ($usuario) {
            $this->context->buildViolation($constraint->message)
                    ->addViolation();

            return true;
        }
    }

}
