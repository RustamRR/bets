<?php
return array(
    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => \Users\Entity\User::class,
                'identity_property' => 'email',
                'credential_property' => 'password'
            ),
        ),
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
            ],
        ],
        'driver' => array(
//            __NAMESPACE__ => array(
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => array(__DIR__ . '/../src/Entity')
//            ),
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'orm_default' => [
                'drivers' => [
                    //'Users\Entity' => __NAMESPACE__,
                    'ZfcUser\Entity'    => 'zfcuser_entity',
                    'Users\Entity'      => 'zfcuser_entity'
                ],
            ],

        ),

    ),
    'controllers' => array(
        'factories' => array(
            \Users\Controller\UsersController::class    => \Users\Controller\UsersControllerFactory::class,
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            \Users\Listener\UsersListener::class        => \Users\Listener\UsersListenerFactory::class,
            \Users\Service\UsersService::class          => \Users\Service\UsersServiceFactory::class,
            \Users\Form\EditForm::class                 => \Users\Form\EditFormFactory::class,
            \Users\Hydrator\UsersHydrator::class        => \Users\Hydrator\UsersHydratorFactory::class,
        )
    ),
    'router' => array(
        'routes' => array(
            'users' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/users',
                    'defaults' => array(
                        'controller' => \Users\Controller\UsersController::class,
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/confirmation',
                            'defaults' => array(
                                'controller' => \Users\Controller\UsersController::class,
                                'action'     => 'confirmation',
                            ),
                        ),
                    ),
                    'edit-user' => [
                        'type' => 'Segment',
                        'options' => [
                            'route'    => '/user-edit/:id',
                            'constraints' => [
                                'id' => '[0-9]+'
                            ],
                            'defaults'=> [
                                'controller' => \Users\Controller\UsersController::class,
                                'action' => 'editUser'
                            ],
                        ],
                        //'may_terminate' => true,
                    ],
                )
            )
        )
    ),
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'account' => ['admin', 'member']
            ]
        ]
    ],
    'view_manager' => array(
        'template_map' => array_merge(
            include __DIR__ . '/../template_map.php',
            include __DIR__ . '/../zfc-user-templatemap.php'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'userEditForm'   => \Users\View\Helper\EditForm::class
        ),
    ),
);
