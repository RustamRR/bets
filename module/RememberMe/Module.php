<?php

namespace RememberMe;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use RememberMe\Options\ModuleOptions;

class Module
{
    public function onBootstrap($e)
    {
        $app     = $e->getParam('application');
        $sm      = $app->getServiceManager();
        $options = $sm->get('goaliorememberme_module_options');
        // Add the default entity driver only if specified in configuration
        /*if ($options->getEnableDefaultEntities()) {

            $chain = $sm->get('doctrine.driver.orm_default');
            $chain->addDriver(new AnnotationDriver(__DIR__ . '/src/Entity'), 'GoalioRememberMeDoctrineORM\Entity');
        }*/
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'goaliorememberme_doctrine_em' => 'doctrine.entitymanager.orm_default',
            ),
            'factories' => array(
                'goaliorememberme_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new ModuleOptions(isset($config['goaliorememberme']) ? $config['goaliorememberme'] : array());
                },
                'goaliorememberme_rememberme_mapper' => function ($sm) {
                    return new \RememberMe\Mapper\RememberMe(
                        $sm->get('goaliorememberme_doctrine_em'),
                        $sm->get('goaliorememberme_module_options')
                    );
                },
            ),
        );
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}