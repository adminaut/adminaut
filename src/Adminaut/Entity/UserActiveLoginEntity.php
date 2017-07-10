<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserActiveLoginEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserActiveLoginRepository")
 * @ORM\Table(name="adminaut_user_active_login")
 * @ORM\HasLifecycleCallbacks()
 */
class UserActiveLoginEntity extends Base
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
     * @ORM\Column(type="string", name="access_token_hash", unique=true)
     * @var string
     */
    protected $accessTokenHash;

    /**
     * @return string
     */
    public function getAccessTokenHash()
    {
        return $this->accessTokenHash;
    }

    //-------------------------------------------------------------------------

    /**
     * UserActiveLoginEntity constructor.
     * @param UserEntity $user
     * @param string $accessTokenHash
     */
    public function __construct(UserEntity $user, $accessTokenHash)
    {
        $this->user = $user;
        $this->accessTokenHash = (string)$accessTokenHash;
    }
}
