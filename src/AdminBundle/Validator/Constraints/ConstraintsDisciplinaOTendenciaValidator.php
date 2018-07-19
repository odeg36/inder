<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsDisciplinaOTendenciaValidator extends ConstraintValidator {

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
        $this->disciplinaOtendencia($constraint);
    }
    
    public function disciplinaOtendencia(Constraint $constraint) {
        if(count($this->object->getDisciplinas()) <= 0 && $this->object->getTendencias()->count() <= 0){
            $this->context->buildViolation("formulario.validar.disciplina.o.tendencia")
                    ->addViolation();
                
                return true;
        }
    }
}
