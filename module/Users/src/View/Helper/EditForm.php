<?php

namespace Users\View\Helper;

use Zend\Form\View\Helper\Form as ZendForm;

class EditForm extends ZendForm
{
    public function __invoke(\Users\Form\EditForm $form)
    {
        return $this->render($form);
    }

    public function render(\Users\Form\EditForm $form){
        return parent::render($form);
    }
}