<?php

namespace Users;

use Users\Listener\UsersListener;
use Users\Options\ModuleOptions;
use Zend\EventManager\EventManager;
use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module
{
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
                'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager',
            ),
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e){
        /** @var $application Application */
        $application = $e->getTarget();
        /** @var $eventManager EventManager */
        $eventManager = $application->getEventManager();
        /** @var $serviceManager ServiceLocatorInterface */
        $serviceManager = $application->getServiceManager();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $usersListener = $serviceManager->get('Users\Listener\UsersListener');
        $eventManager->attach($usersListener);
    }
}
