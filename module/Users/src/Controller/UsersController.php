<?php

namespace Users\Controller;


use Users\Entity\User;
use Users\Form\EditForm;
use Users\Service\UsersService;
use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{
    /** @var  UsersService */
    protected $service;
    /** @var  AuthenticationService */
    protected $authService;
    /** @var Form $form */
    protected $editForm;

    public function __construct(UsersService $service, AuthenticationService $authService, EditForm $editForm)
    {
        $this->setService($service);
        $this->setAuthService($authService);
        $this->setEditForm($editForm);
    }

    /**
     * @return ViewModel
     *
     * Обрабатываем запрос на подтверждение и передаем данные представлению
     */
    public function confirmationAction(){
        $params     = $this->params()->fromQuery();
        $email      = $params["email"];
        $token      = $params["token"];
        // Отправляем данные на проверку в сервис
        $data       = $this->getService()->checkConfirmation($email, $token);

        $view       = new ViewModel();
        $view->setVariable("data", $data);

        return $view;
    }

    public function editUserAction(){
        $id = $this->params()->fromRoute('id')?$this->params()->fromRoute('id'):null;
        /** @var User $user */
        $user = $this->getAuthService()->hasIdentity()?$this->getAuthService()->getIdentity():null;
        if(is_null($id)||is_null($user)){
            return $this->notFoundAction();
        }
        /** @var Form $form */
        $form = $this->getEditForm();
        $form->bind($user);
        /** @var Request $request */
        $request = $this->getRequest();

        if($request->isPost()){
            $dataForm = $request->getPost()->toArray();

            $form->setData($dataForm);
            if($form->isValid()){
                $this->getService()->getEm()->persist($form->getData());
                $this->getService()->getEm()->flush($form->getData());

                $ret = [
                    "status"    => "Данные успешно обновлены!",
                    "content"   => "Спасибо!"
                ];
                $view = new ViewModel();
                $view->setVariable("data", $ret);
                $view->setTemplate("users/users/edit-form-success");

                return $view;
            }
            else{
                //var_dump($form->getMessages()); die();
                $dataError = $form->getMessages();
                $errors = [];
                foreach ($dataError as $k=>$v) {
                    $errors[] = $v;
                }
                $form->setMessages(array("errors" => $errors));
            }
        }
        $view = new ViewModel();
        $view->setVariable("form", $form);
        $view->setVariable("id", $user->getId());
        return $view;
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

    /**
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param AuthenticationService $authService
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return Form
     */
    public function getEditForm()
    {
        return $this->editForm;
    }

    /**
     * @param Form $editForm
     */
    public function setEditForm($editForm)
    {
        $this->editForm = $editForm;
    }
}