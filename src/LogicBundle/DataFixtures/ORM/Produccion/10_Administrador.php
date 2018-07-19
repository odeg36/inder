<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\User;

class FixturesAdministrador extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    public function getOrder() {
        return 10;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $em = $this->container->get('doctrine')->getEntityManager();

        $usuarios = json_decode('[{"tipo_identificacion": "CC","numero_identificacion": "12345678","username": "admin","email": "oscar.estupinan@ito-software.com","enabled": "TRUE","password": "1q2w3e4r5t","roles": "ROLE_SUPER_ADMIN"}]');

        $object = new User();
        $tiposIdentificacion = $em->getRepository('LogicBundle:TipoIdentificacion')->findAll();
        $encoder = $this->container->get('inder.encoder');
        $em = $this->container->get('doctrine')->getManager();
        foreach ($usuarios as $usuario) {
            $tipoIdentificacion = null;
            foreach ($tiposIdentificacion as $tipo) {
                if ($usuario->{'tipo_identificacion'} == $tipo->getAbreviatura()) {
                    $tipoIdentificacion = $tipo;
                }
            }
            $registro = $em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('username' => $tipoIdentificacion->getAbreviatura() . $usuario->{'numero_identificacion'}));
            if (!$registro) {
                $object->setTipoIdentificacion($tipoIdentificacion);
                $object->setNumeroIdentificacion($usuario->{'numero_identificacion'});
                $object->setUsername($tipoIdentificacion->getAbreviatura() . $usuario->{'numero_identificacion'});
                $object->setUsernameCanonical($usuario->{'username'});
                $object->setFirstname($usuario->{'username'});
                $object->setEmail($usuario->{'email'});
                $object->setEmailCanonical($usuario->{'email'});
                $object->setEnabled($usuario->{'enabled'});
                $password = $encoder->encodePassword($usuario->{'password'}, $object->getSalt());
                $object->setPassword($password);
                $object->setRoles(array($usuario->{'roles'}));
                $manager->persist($object);
            }
        }
        $manager->flush(); 
    }

}
