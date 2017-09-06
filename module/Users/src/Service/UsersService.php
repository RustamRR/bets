<?php

namespace Users\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception\Exception;
use Users\Entity\Confirmations;
use Users\Entity\User;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Part;
use Zend\Mail;
use Zend\Validator\File\MimeType;

class UsersService
{
    /** @var  EntityManager */
    protected $em;
    /** @var  EntityRepository */
    protected $confirmRepo;
    /** @var  EntityRepository */
    protected $userRepo;
    /** @var  EntityRepository */
    protected $mails;

    /**
     * UsersService constructor.
     * @param $em
     * @param $confirmRepo
     * @param $userRepo
     * @param $mails
     */
    public function __construct(EntityManager $em, $confirmRepo, $userRepo, $mails){
        $this->setEm($em);
        $this->setConfirmRepo($confirmRepo);
        $this->setUserRepo($userRepo);
        $this->setMails($mails);
    }

    /**
     * @param User $user
     *
     * Метод, добавляющий новую запись в таблицу с подтверждениями и передающий данные
     * для отправки специального письма пользователю
     */
    public function setConfirmationEmail(User $user){
        $code = md5($user->getEmail().time());
        $confirmationsEntity = new Confirmations();
        $confirmationsEntity->setUser($user);
        $confirmationsEntity->setCode($code);
        $confirmationsEntity->setStatus(false);

        $this->sendConfMail($user->getUsername(), $user->getEmail(), $code);

        $this->getEm()->persist($confirmationsEntity);
        $this->getEm()->flush($confirmationsEntity);
    }

    public function checkConfirmation($email, $code){
        $ret = [];
        /** @var Confirmations $confirmations */
        $confirmations = $this->getConfirmRepo()->findOneBy(array('code' => $code));
        // Если в таблице подтверждений ни найдено данных по указанному коду и email
        if(is_null($confirmations)){
            $ret = [
                "status"    => "fail",
                "content"   => "Данных об аккаунте не найдено"
            ];
            return $ret;
        }
        // Если в таблице подтверждений запись найдена, но аккаунт уже подтвержден
        // или в таблице подтверждений статус записи стоит в false, а в аккаунте пользователя
        // state не равен null, а также если email из ссылки не принадлежит пользователю
        if($confirmations->isStatus()
            || !is_null($confirmations->getUser()->getState())
            || $confirmations->getUser()->getEmail() != $email){
            $ret = [
                "status"    => "fail",
                "content"   => "Аккаунт уже подтвержден"
            ];
            return $ret;
        }
        // Если условия выше не подтвердились, то меняем статус у записи
        // в таблице подтверждений и активируем аккаунт пользователя
        $confirmations->setStatus(true);
        $this->getEm()->persist($confirmations);
        $this->getEm()->flush($confirmations);

        $user = $confirmations->getUser();
        $user->setState(1);
        $this->getEm()->persist($user);
        $this->getEm()->flush($user);

        $ret = [
            "status"    => "success",
            "content"   => "Аккаунт подтвержден! Теперь вы можете авторизоваться на сайте, используя логин и пароль, 
            указанные при регистрации."
        ];
        return $ret;
    }

    /**
     * @param $username
     * @param $email
     * @param $code
     * @throws Exception
     *
     * Метод, отправляющий специальное письмо для активации аккаунта
     */
    public function sendConfMail($username, $email, $code){
        $bodyTemplate =
            '%s, спасибо за регистрацию в AdminPanel! Пожалуйста, подтвердите свою почту по <a href="%s">ссылке</a>';
        $link = $this->createConfirmationLink($code, $email);
        if(is_null($link)){
            throw new Exception("Не достаточно данных для формирования сообщения на верификацию аккаунта");
        };
        // формируем body
        $body = sprintf($bodyTemplate, $username, $link);

        $html = new Part($body);
        $html->type = "text/html";

        $text = new Part($body);
        $text->type = "text/plain";

        $body = new \Zend\Mime\Message();
        $body->setParts(array($text, $html));

        $mail = new Message();
        $mail->addFrom($this->getMails("noreply"))
            ->addTo($email)
            ->addReplyTo($this->getMails("noreply"))
            ->setSender($this->getMails("noreply"), "Notification AdminPanel")
            ->setSubject("Активация аккаунта")
            ->setEncoding("UTF-8")
            ->setBody($body)
            ->getHeaders()->get('content-type')->setType('multipart/alternative');

        $transport = new Mail\Transport\Sendmail('-freturn_to_me@example.com');
        $transport->send($mail);
        /*$transport = new Sendmail();
        $transport->send($mail);*/

    }

    /**
     * @param $code
     * @param $email
     * @return null|string
     *
     * Метод формирует ссылку на подтверждение аккаунта
     */
    public function createConfirmationLink($code, $email){
        if(is_null($code) || is_null($email)) return null;
        $linkTemplate = 'http://adminpanel.loc/users/confirmation?email=%s&token=%s';
        $link = sprintf($linkTemplate, $email, $code);
        return $link;
    }

    /**
     * @return mixed
     *
     * В методе проверяется, есть ли нужных тип email и возвращает его при наличии
     */
    public function getMails($addr)
    {
        if(array_key_exists($addr, $this->mails)) return $this->mails[$addr];
        return null;
    }

    /**
     * @param mixed $mails
     */
    public function setMails($mails)
    {
        $this->mails = $mails;
    }

    /**
     * @return EntityRepository
     */
    public function getConfirmRepo()
    {
        return $this->confirmRepo;
    }

    /**
     * @param EntityRepository $confirmRepo
     */
    public function setConfirmRepo($confirmRepo)
    {
        $this->confirmRepo = $confirmRepo;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityRepository
     */
    public function getUserRepo()
    {
        return $this->userRepo;
    }

    /**
     * @param EntityRepository $userRepo
     */
    public function setUserRepo($userRepo)
    {
        $this->userRepo = $userRepo;
    }
}