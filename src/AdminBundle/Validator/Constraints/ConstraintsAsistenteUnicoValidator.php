<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsAsistenteUnicoValidator extends ConstraintValidator {

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
        
        $this->asistentes($constraint);
    }

    public function asistentes(Constraint $constraint) {
        $asistencias = $this->getAsitencia();
        if (!$asistencias) {
            return false;
        }
        
        $keys = [];
        
        foreach ($asistencias as $key => $asistencia) {
            if(!$asistencia->getUsuario()){
                continue;
            }
            if(array_search($asistencia->getUsuario()->getId(), $keys) !== false){
                $this->context->buildViolation($constraint->message, ["%usuario%" => $asistencia->getUsuario()->getNumeroIdentificacion()])
                    ->atPath('asistencias')
                    ->addViolation();

                return true;
            }
            
            array_push($keys, $asistencia->getUsuario()->getId());
        }
    }
    
    public function getAsitencia() {
        if($this->object instanceof \LogicBundle\Entity\Oferta){
            return $this->object->getAsistencias();
        }else if($this->object instanceof \LogicBundle\Entity\Reserva){
            return $this->object->getAsistenciaReservas();
        }
    }
    

}
