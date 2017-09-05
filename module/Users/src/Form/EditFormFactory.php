<?php

namespace Users\Form;


use Users\Form\Filter\EditFilter;
use Users\Hydrator\UsersHydrator;
use Users\Validator\NoRecordExistsEdit;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditFormFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        if ($formElementManager instanceof FormElementManager) {
            $sm = $formElementManager->getServiceLocator();
            $fem = $formElementManager;
        } else {
            $sm = $formElementManager;
            $fem = $sm->get('formElementManager');
        }

        $options = $sm->get('zfcuser_module_options');
        $form = new EditForm('form', []);
        $form->getFormFactory()->setFormElementManager($fem);
        $form->setHydrator($sm->get(UsersHydrator::class));
        $form->setInputFilter(new EditFilter(
            new NoRecordExistsEdit(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'email'
            )),
            new NoRecordExistsEdit(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'username'
            )),
            $options
        ));

        return $form;
    }
}