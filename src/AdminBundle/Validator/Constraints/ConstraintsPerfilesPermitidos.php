<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsPerfilesPermitidos extends Constraint {

    public $message = 'formulario.validar.perfiles.permitidos';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
