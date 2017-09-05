<?php

namespace Users\Listener;

use Users\Service\UsersService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsersListenerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $casesService = $serviceLocator->get(UsersService::class);

        return new UsersListener($casesService);
    }
}