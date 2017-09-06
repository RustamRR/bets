<?php

namespace Users\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Confirmations
 * @package Users\Entity
 *
 * @ORM\Entity(repositoryClass="ConfirmationsRepository")
 * @ORM\Table(name="confirmations")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class Confirmations
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
     * @var User
     * @ORM\OneToOne(targetEntity="\Users\Entity\User")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="users_id", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $user;
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false)
     */
    protected $code;
    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    protected $status;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}