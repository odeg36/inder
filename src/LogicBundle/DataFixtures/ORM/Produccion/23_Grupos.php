<?php

namespace LogicBundle\DataFixtures\ORM\Produccion;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\UserBundle\Entity\Group;

class FixturesGrupos extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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

    public function getOrder() {
        return 23;
    }

    public function load(ObjectManager $manager) {
        $objects = [
            [
                "name" => "Administrador SIM",
                "roles" => ["ROLE_ADMIN", "ROLE_SUPER_ADMIN", "ROLE_ADMIN_ADMIN_OFERTA_LIST", "ROLE_ADMIN_ADMIN_OFERTA_CREATE", "ROLE_ADMIN_ADMIN_OFERTA_EDIT", "ROLE_GESTOR_TERRITORIAL"]
            ],
            [
                "name" => "Gestor territorial",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_SONATA_USER_ADMIN_USER_LIST",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_LIST",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_CREATE",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_EDIT",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_VIEW",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_EXPORT",
                    "ROLE_ADMIN_ADMIN_OFERTA_LIST", "ROLE_ADMIN_ADMIN_OFERTA_CREATE", "ROLE_ADMIN_ADMIN_OFERTA_EDIT", "ROLE_GESTOR_TERRITORIAL",
                    "ROLE_ADMIN_ADMIN_RESERVA_LIST", "ROLE_ADMIN_ADMIN_RESERVA_CREATE", "ROLE_ADMIN_ADMIN_RESERVA_EDIT", "ROLE_ADMIN_ADMIN_RESERVA_VIEW", "ROLE_ADMIN_ADMIN_RESERVA_EXPORT"
                ]
            ],
            [
                "name" => "Líder Estrategia",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_REPORTE_ESTADISTICAS", "ROLE_SONATA_USER_ADMIN_USER_EDIT", "ROLE_ADMIN_ADMIN_PLAN_ANUAL_METODOLOGICO_LIST", 
                    "ROLE_ADMIN_ADMIN_PLAN_ANUAL_METODOLOGICO_CREATE", "ROLE_ADMIN_ADMIN_PLAN_ANUAL_METODOLOGICO_EDIT", 
                    "ROLE_ADMIN_ADMIN_PLAN_ANUAL_METODOLOGICO_VIEW", "ROLE_ADMIN_ADMIN_PLAN_ANUAL_METODOLOGICO_EXPORT"
                ]
            ],
            [
                "name" => "Lider Escenarios",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_LIST", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_CREATE", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EDIT", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_VIEW", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EXPORT",
                    "ROLE_ADMIN_ADMIN_RESERVA_LIST", "ROLE_ADMIN_ADMIN_RESERVA_CREATE", "ROLE_ADMIN_ADMIN_RESERVA_EDIT", "ROLE_ADMIN_ADMIN_RESERVA_VIEW", "ROLE_ADMIN_ADMIN_RESERVA_EXPORT"
                ]
            ],
            [
                "name" => "Lider Eventos",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                ]
            ],
            [
                "name" => "Lider Componente metodologico",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                ]
            ],
            [
                "name" => "Gestor Escenarios",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT", "ROLE_GESTOR_ESCENARIO",
                    "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_LIST", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_CREATE", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EDIT", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_VIEW", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EXPORT",
                    "ROLE_ADMIN_ADMIN_RESERVA_LIST", "ROLE_ADMIN_ADMIN_RESERVA_CREATE", "ROLE_ADMIN_ADMIN_RESERVA_EDIT", "ROLE_ADMIN_ADMIN_RESERVA_VIEW", "ROLE_ADMIN_ADMIN_RESERVA_EXPORT"
                ]
            ],
            [
                "name" => "Gestor Eventos",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT", "ROLE_ADMIN_ADMIN_EVENTO_CREATE", "ROLE_ADMIN_ADMIN_EVENTO_EDIT", "ROLE_ADMIN_ADMIN_EVENTO_LIST", "ROLE_ADMIN_ADMIN_EVENTO_VIEW", "ROLE_ADMIN_ADMIN_EVENTO_EXPORT", "ROLE_ADMIN_ADMIN_EQUIPO_EVENTO_LIST", "ROLE_ADMIN_ADMIN_EQUIPO_EVENTO_CREATE", "ROLE_ADMIN_ADMIN_EQUIPO_EVENTO_EDIT", "ROLE_ADMIN_ADMIN_EQUIPO_EVENTO_VIEW", "ROLE_ADMIN_ADMIN_EQUIPO_EVENTO_EXPORT",
                    "ROLE_ADMIN_ADMIN_JUGADOR_EVENTO_LIST", "ROLE_ADMIN_ADMIN_JUGADOR_EVENTO_CREATE", "ROLE_ADMIN_ADMIN_JUGADOR_EVENTO_EDIT", "ROLE_ADMIN_ADMIN_JUGADOR_EVENTO_VIEW", "ROLE_ADMIN_ADMIN_JUGADOR_EVENTO_EXPORT", "ROLE_ADMIN_ADMIN_SANCION_LIST", "ROLE_ADMIN_ADMIN_SANCION_CREATE", "ROLE_ADMIN_ADMIN_SANCION_EDIT", "ROLE_ADMIN_ADMIN_SANCION_VIEW", "ROLE_ADMIN_ADMIN_SANCION_EXPORT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_UNO_LIST",
                    "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_UNO_CREATE", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_UNO_EDIT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_UNO_VIEW", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_UNO_EXPORT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_DOS_LIST", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_DOS_CREATE", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_DOS_EDIT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_DOS_VIEW", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_DOS_EXPORT",
                    "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_TRES_LIST", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_TRES_CREATE", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_TRES_EDIT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_TRES_VIEW", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_TRES_EXPORT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_CUATRO_LIST", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_CUATRO_CREATE", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_CUATRO_EDIT", "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_CUATRO_VIEW",
                    "ROLE_ADMIN_ADMIN_ENCUENTRO_SISTEMA_CUATRO_EXPORT", "ROLE_ADMIN_ADMIN_TIPO_FALTA_LIST", "ROLE_ADMIN_ADMIN_TIPO_FALTA_CREATE", "ROLE_ADMIN_ADMIN_TIPO_FALTA_EDIT", "ROLE_ADMIN_ADMIN_TIPO_FALTA_VIEW", "ROLE_ADMIN_ADMIN_TIPO_FALTA_EXPORT", "ROLE_ADMIN_ADMIN_SUB_CATEGORIA_EVENTO_LIST", "ROLE_ADMIN_ADMIN_SUB_CATEGORIA_EVENTO_CREATE", "ROLE_ADMIN_ADMIN_SUB_CATEGORIA_EVENTO_EDIT", "ROLE_ADMIN_ADMIN_SUB_CATEGORIA_EVENTO_VIEW", "ROLE_ADMIN_ADMIN_SUB_CATEGORIA_EVENTO_EXPORT",
                    "ROLE_ADMIN_ADMIN_CATEGORIA_EVENTO_LIST","ROLE_ADMIN_ADMIN_CATEGORIA_EVENTO_CREATE","ROLE_ADMIN_ADMIN_CATEGORIA_EVENTO_EDIT","ROLE_ADMIN_ADMIN_CATEGORIA_EVENTO_VIEW","ROLE_ADMIN_ADMIN_CATEGORIA_EVENTO_EXPORT"
                ]
            ],
            [
                "name" => "Gestor Componente Metodológico",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT"
                ]
            ],
            [
                "name" => "Técnico",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT"
                ]
            ],
            [
                "name" => "Gestor organismos deportivos",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_SONATA_USER_ADMIN_USER_LIST", "ROLE_SONATA_USER_ADMIN_USER_CREATE", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_LIST", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EDIT"
                ]
            ],
            [
                "name" => "Formador",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_FORMADOR",
                    "ROLE_SONATA_USER_ADMIN_USER_CREATE",
                    "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_SONATA_USER_ADMIN_USER_LIST", "ROLE_SONATA_USER_ADMIN_USER_VIEW",
                    "ROLE_ADMIN_ADMIN_OFERTA_LIST", "ROLE_ADMIN_ADMIN_OFERTA_VIEW",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_LIST", "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_CREATE",
                    "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_EDIT", "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_VIEW", "ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_EXPORT","ROLE_ADMIN_ADMIN_PLAN_CLASE_EXPORT",
                    "ROLE_ADMIN_ADMIN_PLAN_CLASE_LIST","ROLE_ADMIN_ADMIN_PLAN_CLASE_EDIT","ROLE_ADMIN_ADMIN_PLAN_CLASE_CREATE","ROLE_ADMIN_ADMIN_PLAN_CLASE_VIEW"
                ]
            ],
            [
                "name" => "Registrado (Persona Natural)",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_LIST", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_VIEW",
                    "ROLE_ADMIN_ADMIN_BANNER_VIEW", "ROLE_PERSONANATURAL", 'ROLE_ADMIN_ADMIN_OFERTA_LIST', 'ROLE_ADMIN_ADMIN_OFERTA_VIEW', 'ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_LIST',
                    "ROLE_ADMIN_ADMIN_RESERVA_LIST", "ROLE_ADMIN_ADMIN_EVENTO_LIST", "ROLE_ADMIN_ADMIN_EVENTO_VIEW" ,"ROLE_ADMIN_ADMIN_EVENTO_LIST" ,"ROLE_ADMIN_ADMIN_RESERVA_CREATE", "ROLE_ADMIN_ADMIN_RESERVA_EDIT", "ROLE_ADMIN_ADMIN_RESERVA_VIEW", "ROLE_ADMIN_ADMIN_RESERVA_EXPORT",
                    'ROLE_ADMIN_ADMIN_PREINSCRIPCION_OFERTA_CREATE'
                ]
            ],
            [
                "name" => "Registrado (Organismos deportivos)",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_LIST", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_EDIT", "ROLE_ADMIN_ADMIN_ESCENARIO_DEPORTIVO_VIEW",
                    "ROLE_ORGANISMO_DEPORTIVO", "ROLE_ADMIN_ADMIN_ORGANIZACION_DEPORTIVA_LIST", "ROLE_ADMIN_ADMIN_ORGANIZACION_DEPORTIVA_EDIT", "ROLE_ADMIN_ADMIN_ORGANIZACION_DEPORTIVA_VIEW",
                    "ROLE_ADMIN_ADMIN_RESERVA_LIST", "ROLE_ADMIN_ADMIN_RESERVA_CREATE", "ROLE_ADMIN_ADMIN_RESERVA_EDIT", "ROLE_ADMIN_ADMIN_RESERVA_VIEW", "ROLE_ADMIN_ADMIN_RESERVA_EXPORT"
                ]
            ],
            [
                "name" => "Medico Deportologo",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT"
                ]
            ],
            [
                "name" => "Psicologo",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT"
                ]
            ],
            [
                "name" => "Fisioterapeuta",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT", "ROLE_MEDICO"
                ]
            ],
            [
                "name" => "Entrenador Técnico",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT"
                ]
            ],
            [
                "name" => "Gestor cultura D",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_SONATA_USER_ADMIN_USER_EDIT",
                    "ROLE_ADMIN_ADMIN_BANNER_LIST", "ROLE_ADMIN_ADMIN_BANNER_CREATE", "ROLE_ADMIN_ADMIN_BANNER_EDIT", "ROLE_ADMIN_ADMIN_BANNER_VIEW", "ROLE_ADMIN_ADMIN_BANNER_EXPORT"
                ]
            ],
            [
                "name" => "Deportista",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_DEPORTISTA"
                ]
            ],
            [
                "name" => "Médico",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_MEDICO"
                ]
            ],
            [
                "name" => "Nutricionista",
                "roles" => [
                    "ROLE_ADMIN", "ROLE_NUTRICIONISTA", "ROLE_MEDICO"
                ]
            ]
        ];

        $em = $this->container->get('doctrine')->getManager();
        foreach ($objects as $object) {
            $registro = $em->getRepository('ApplicationSonataUserBundle:Group')->findOneBy(array('name' => $object["name"]));
            if (!$registro) {
                $entity = new Group([]);
                $entity->setName($object["name"]);
                $entity->setRoles($object["roles"]);

                $this->setReference("group_" . $object["name"], $entity);
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

}
