<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */


return array(
    'doctrine' => [
        'migrations_configuration' => [
            'orm_default' => [
                'namespace' => 'OrmDefaultMigrations',
                'directory' => __DIR__ . '/../../migrations/orm_default',
                'table' => 'doctrine_migration_versions',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'params' => [
                    'host' => '',
                    'port' => '',
                    'user' => '',
                    'password' => '',
                    'dbname' => '',
                    'driver' => '',
                ]
            ],
        ]
    ],
    'service_manager' => [
        'factories' => [
            'doctrine.entitymanager.orm_default' => new DoctrineORMModule\Service\EntityManagerFactory('orm_default'),
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'standard_identity' => function ($sm) {
                $roles = array('guest','member','admin');
                $identity = new \ZfcRbac\Identity\StandardIdentity($roles);
                return $identity;
            },
        ],
    ],
    'assetic_configuration' => [ //настройки модуля assetic (загрузка клиентских файлов)
        'debug' => false,
        'cacheEnabled' => true,
        'cachePath' => __DIR__ . '/../../data/cache',
        'webPath' => __DIR__ . '/../../public/csgobets/assets',//папка, куда assetic сохраняет клиентские файлы из всех модулей.
        'basePath' => 'csgobets/assets',
        'acceptableErrors' => [
            'error-rbac',
        ],
    ], // assets
    'zfcrbac' => [
        'firewallRoute' => false,
        'firewallController' => false,
        'anonymousRole' => 'guest',
        'identity_provider' => 'standard_identity',
        'providers' => [
            'ZfcRbac\Provider\AdjacencyList\Role\DoctrineDbal' => array(
                'connection' => 'doctrine.connection.orm_default',
                'options' => array(
                    'table'         => 'rbac_role',
                    'id_column'     => 'role_id',
                    'name_column'   => 'role_name',
                    'join_column'   => 'parent_role_id'
                )
            ),
            'ZfcRbac\Provider\Generic\Permission\DoctrineDbal' => array(
                'connection' => 'doctrine.connection.orm_default',
                'options' => array(
                    'permission_table'      => 'rbac_permission',
                    'role_table'            => 'rbac_role',
                    'role_join_table'       => 'rbac_role_permission',
                    'permission_id_column'  => 'perm_id',
                    'permission_join_column'=> 'perm_id',
                    'role_id_column'        => 'role_id',
                    'role_join_column'      => 'role_id',
                    'permission_name_column'=> 'perm_name',
                    'role_name_column'      => 'role_name'
                )
            ),
        ],
    ],
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home',
            ),
            array(
                'label' => 'Авторизация',
                'route' => 'zfcuser/login'
            ),
            array(
                'label' => 'Регистрация',
                'route' => 'zfcuser/register'
            )
//            array(
//                'label' => 'Page #1',
//                'route' => 'page-1',
//                'pages' => array(
//                    array(
//                        'label' => 'Child #1',
//                        'route' => 'page-1-child',
//                    ),
//                ),
//            ),
//            array(
//                'label' => 'Page #2',
//                'route' => 'page-2',
//            ),
        ),
    ),
    'controller_plugins' =>     [
        'invokables' => [
            'isGranted' => 'ZfcRbac\Controller\Plugin\IsGranted',
        ],
    ],
    'translator' => array(
        'locale' => 'ru_RU',
    )
);