<?php

namespace Users\Listener;

use Users\Entity\User;
use Users\Service\UsersService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\SharedEventManager;
use ZfcUser\Form\Register;
use ZfcUser\Form\RegisterFilter;

class UsersListener implements ListenerAggregateInterface
{
    /** @var  UsersService */
    protected $service;
    /** @var array  */
    protected $listeners = array();

    public function __construct(UsersService $service)
    {
        $this->setService($service);
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        /** @var SharedEventManager $sharedEventManager */
        $sharedEventManager = $events->getSharedManager();

        $this->listeners[] = $sharedEventManager->attach('ZfcUser\Service\User', 'register.post',
            [$this, 'onPostregistActions'], 100);
        // Добавляю собственные поля в форму регистрации
        $this->listeners[] = $sharedEventManager->attach('ZfcUser\Form\Register', 'init',
            [$this, 'addCustonFields'], 100);
        // Добавляю фильтры к вышеуказанным полям
        $this->listeners[] = $sharedEventManager->attach('ZfcUser\Form\RegisterFilter', 'init',
            [$this, 'addCustonFilters'], 100);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onPostregistActions(EventInterface $event){
        /** @var User $user */
        $user = $event->getParam("user");
        return $this->getService()->setConfirmationEmail($user);
    }

    public function addCustonFields(EventInterface $event){
        /* @var $form Register */
        $form = $event->getTarget();

        $form->add(
            array(
                'name' => 'firstname',
                'type' => 'text',
                'options' => array(
                    'label' => 'Имя',
                ),
            )
        );

        $form->add(
            array(
                'name' => 'lastname',
                'type' => 'text',
                'options' => array(
                    'label' => 'Фамилия',
                ),
            )
        );
    }

    public function addCustonFilters(EventInterface $event){
        /** @var RegisterFilter $form */
        $filter = $event->getTarget();

        $filter->add(array(
                'name'       => 'firstname',
                'required'   => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                ),
            )
        );

        $filter->add(array(
                'name'      => 'lastname',
                'required'  => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                ),
            )
        );
    }

    /**
     * @return UsersService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param UsersService $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }
}