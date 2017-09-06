<?php

namespace Users\Hydrator;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsersHydratorFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $em */
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        return new UsersHydrator($em);
    }
}