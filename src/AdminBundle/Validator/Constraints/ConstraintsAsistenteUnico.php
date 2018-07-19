<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsAsistenteUnico extends Constraint {

    public $message = 'formulario.validar.asistencia.unico';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
