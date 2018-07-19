<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsUsuarioUnico extends Constraint {

    public $message = 'formulario.validar.usuario.unico';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
