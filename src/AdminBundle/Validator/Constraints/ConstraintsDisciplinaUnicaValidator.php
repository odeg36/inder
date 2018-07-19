<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsDisciplinaUnicaValidator extends ConstraintValidator {

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

        $this->disciplinaUnica($constraint);
    }

    public function disciplinaUnica(Constraint $constraint) {
        $disciplinas = [];
        foreach ($this->object->getDisciplinas() as $key => $disciplina) {
            if (!$disciplina->getDisciplina()) {
                return true;
            }

            $id = $disciplina->getDisciplina()->getId();
            if (key_exists($id, $disciplinas)) {
                $this->context->buildViolation("formulario.validar.disciplina.unica", ["%disciplina%" => $disciplina->getDisciplina()->getNombre()])
                        ->addViolation();

                return true;
            }

            $disciplinas[$id] = $id;
        }
    }

}
