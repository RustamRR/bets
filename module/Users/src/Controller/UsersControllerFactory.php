<?php

namespace Users\Controller;


use Users\Form\EditForm;
use Users\Service\UsersService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class UsersControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator  = $serviceLocator->getServiceLocator();
        $service        = $parentLocator->get(UsersService::class);
        /** @var \Zend\Authentication\AuthenticationService $authService */
        $authService    = $parentLocator->get('Zend\Authentication\AuthenticationService');
        /** @var Form $form */
        $editForm       = $parentLocator->get(EditForm::class);

        return new UsersController($service, $authService, $editForm);
    }
}