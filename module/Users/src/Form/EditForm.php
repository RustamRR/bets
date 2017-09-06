<?php

namespace Users\Form;

use Users\Form\Fieldset\EditFieldset;
use Zend\Form\Element\Button;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

class EditForm extends Form
{
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' => 'Логин',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Пароль',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'type' => 'password',
            'options' => array(
                'label' => 'Подтверждение пароля',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(
            array(
                'name' => 'firstname',
                'type' => 'text',
                'options' => array(
                    'label' => 'Имя',
                ),
            )
        );

        $this->add(
            array(
                'name' => 'lastname',
                'type' => 'text',
                'options' => array(
                    'label' => 'Фамилия',
                ),
            )
        );

        $submitElement = new Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes(array(
                'type'  => 'submit',
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
    }

    public function init()
    {

    }
}