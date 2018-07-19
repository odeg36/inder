<?php

namespace AdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConstraintsPerfilesPermitidosValidator extends ConstraintValidator {

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

        $this->perfilesPermitidos($constraint);
    }

    public function perfilesPermitidos(Constraint $constraint) {
        foreach ($this->object->getOrganismosorganizacion() as $key => $organismoOrganizacion) {
            $maximo = 0;
            $minimo = 0;
            $tipoOrganoNombre = "";

            if ($organismoOrganizacion->getTipoOrgano()) {
                $maximo = $organismoOrganizacion->getTipoOrgano()->getMaximo();
                $minimo = $organismoOrganizacion->getTipoOrgano()->getMinimo();
                $tipoOrganoNombre = $organismoOrganizacion->getTipoOrgano()->getNombre();
            }

            $perfiles = $organismoOrganizacion->getPerfilOrganismos()->count();

            if ($perfiles > $maximo) {
                $this->context->buildViolation("formulario.validar.perfiles.validos.maximo", ["%organo%" => $tipoOrganoNombre, "%limite%" => $maximo])
                        ->addViolation();
            }

            if ($perfiles < $minimo) {
                $this->context->buildViolation("formulario.validar.perfiles.validos.minimo", ["%organo%" => $tipoOrganoNombre, "%limite%" => $minimo])
                        ->addViolation();
            }
        }
    }

}
