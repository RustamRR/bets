<?php

namespace Users\Service;


use Doctrine\ORM\EntityManager;
use Users\Entity\Confirmations;
use Users\Entity\HierarchicalRole;
use Users\Entity\User;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UsersServiceFactory implements FactoryInterface
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
        $em             = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $confirmRepo    = $em->getRepository(Confirmations::class);
        $userRepo       = $em->getRepository(User::class);

        $config         = $serviceLocator->get('Config');
        $mails          = $config["emails"];

        return new UsersService($em, $confirmRepo, $userRepo, $mails);
    }
}