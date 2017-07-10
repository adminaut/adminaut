<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserFailedLoginEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserFailedLoginRepository")
 * @ORM\Table(name="adminaut_user_failed_login")
 * @ORM\HasLifecycleCallbacks()
 */
class UserFailedLoginEntity extends Base
{
    /**
     * @ORM\Column(type="integer", name="user_id")
     * @var int
     */
    protected $userId;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    //-------------------------------------------------------------------------

    /**
     * Owning side.
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="failedLogins")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var UserEntity
     */
    protected $user;

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    //-------------------------------------------------------------------------

    /**
     * UserFailedLoginEntity constructor.
     * @param UserEntity $user
     */
    public function __construct(UserEntity $user)
    {
        $this->user = $user;
    }
}
