<?php

namespace AdminBundle\Form;

use LogicBundle\Entity\Reserva;
use LogicBundle\Entity\Municipio;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformacionExtraUsuarioType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $noVacio = array(
            new NotBlank(array(
                'message' => 'formulario_registro.no_vacio',
                    ))
        );

        $oferta = $options['oferta'];
        $em = $options['em'];
        $container = $options['container'];
        $camposUsuarioEstrategia = $oferta->getEstrategia()->getCamposUsuario();
        $camposUsuario = $em->getRepository('LogicBundle:CampoUsuario')->findAll();
        $tiposCampos = $container->getParameter('tipos_campos');
        foreach ($camposUsuario as $campoUsuario) {
            $existe = false;
            foreach ($camposUsuarioEstrategia as $campoUsuarioEstrategia) {
                if ($campoUsuario->getNombreMapeado() == $campoUsuarioEstrategia->getNombreMapeado()) {
                    $existe = true;
                }
            }
            if ($existe) {
                $opciones = [
                    "required" => true,
                    "constraints" => $noVacio,
                ];
                if ($campoUsuario->getTipo() == $tiposCampos['boolean']) {
                    $opciones['required'] = false;
                    $opciones['constraints'] = null;
                }
                $builder
                        ->add($campoUsuario->getNombreMapeado(), null, $opciones)
                ;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => InformacionExtraUsuario::class,
            'oferta' => null,
            'em' => null,
            'container' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'informacion_extra';
    }

}
