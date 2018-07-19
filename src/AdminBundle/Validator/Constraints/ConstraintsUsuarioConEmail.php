<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ConstraintsUsuarioConEmail extends Constraint {

    public $message_sin_email = 'formulario.validar.usuario.sin_email';
    public $message_no_existe = 'formulario.validar.usuario.no_existe';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}
