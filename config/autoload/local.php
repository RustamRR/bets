<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */

return [
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
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => [
                    'host' => 'localhost',
                    'port' => '5432',
                    'user' => 'admin_csgobets',
                    'password' => '9rTJSovoRK',
                    'dbname' => 'admin_csgobets',
                    'driver' => 'pdo_pgsql',
                ]
            ],
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'AsseticCacheBuster' => 'AsseticBundle\CacheBuster\LastModifiedStrategy',
        ],
        'factories' => [
            'doctrine.entitymanager.orm_default' => new DoctrineORMModule\Service\EntityManagerFactory('orm_default'),
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'ows_sql_logger' => function($sm){
                $log = new \Zend\Log\Logger();
                $writer = new \Zend\Log\Writer\Stream('./data/logs/sql.log');
                $log->addWriter($writer);

                $sqllog = new \Application\Log\SqlLogger($log);
                return $sqllog;
            },
            'standard_identity' => function ($sm) {
                $roles = array('guest','member','admin');
                $identity = new \ZfcRbac\Identity\StandardIdentity($roles);
                return $identity;
            },
        ],

    ],
    'emails' => [
        'noreply' => 'noreply@csgo-bets.win'
    ],
    'zfc_rbac' => [
        'role_provider' => [
            'ZfcRbac\Role\ObjectRepositoryRoleProvider' => [
                'object_manager'     => 'doctrine.entitymanager.orm_default', // alias for doctrine ObjectManager
                'class_name'         => 'Users\Entity\HierarchicalRole', // FQCN for your role entity class
                'role_name_property' => 'name', // Name to show
            ],
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
    'session' => [
        'config' => [
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => [
                'name' => 'csgobets',
            ],
        ],
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => [
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ],
    ],
];