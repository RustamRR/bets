<?php

return array(
    'doctrine' => array(
        /*'driver' => array(
            'goalioremembermedoctrineorm_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'GoalioRememberMeDoctrineORM\Entity'  => 'goalioremembermedoctrineorm_entity'
                )
            )
        ),*/
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
            ],
        ],
        'driver' => array(
            __NAMESPACE__ => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'orm_default' => [
                'drivers' => [
                    'GoalioRememberMe\Entity'  => __NAMESPACE__,
                    'GoalioRememberMeDoctrineORM\Entity' => __NAMESPACE__,
                    'RememberMe\Entity' => __NAMESPACE__
                ],
            ],

        ),
    ),
);