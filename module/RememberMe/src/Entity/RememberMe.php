<?php

namespace RememberMe\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RememberMe
 * @package RememberMe\Entity
 *
 * @ORM\Entity(repositoryClass="RememberMeRepository")
 * @ORM\Table(name="user_remember_me")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class RememberMe
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", unique=true)
     */
    protected $sid;
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", unique=true)
     */
    protected $token;
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", unique=true)
     */
    protected $user_id;

    /**
     * @return string
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * @param string $sid
     */
    public function setSid($sid)
    {
        $this->sid = $sid;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}