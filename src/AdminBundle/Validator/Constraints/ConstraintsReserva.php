<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsReserva extends Constraint {
    public $message = 'formulario.validar.email.unico';
    
    public function validatedBy(){
        return get_class($this).'Validator';
    }
}