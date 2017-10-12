<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;

//use Zend\Http\PhpEnvironment\RemoteAddress;
//use Zend\Session\Validator\HttpUserAgent;

/**
 * Class UserAccessTokenEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserAccessTokenRepository")
 * @ORM\Table(name="adminaut_user_access_token")
 * @ORM\HasLifecycleCallbacks()
 */
class UserAccessTokenEntity implements AdminautEntityInterface
{
    use AdminautEntityTrait;

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
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="accessTokens")
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
     * @ORM\Column(type="string", name="hash", unique=true)
     * @var string
     */
    protected $hash;

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

//    //-------------------------------------------------------------------------
//
//    /**
//     * @ORM\Column(type="string", name="ip_address", nullable=true)
//     * @var string
//     */
//    protected $ipAddress;
//
//    /**
//     * @return string
//     */
//    public function getIpAddress()
//    {
//        return $this->ipAddress;
//    }
//
//    /**
//     * @param string $ipAddress
//     */
//    public function setIpAddress($ipAddress)
//    {
//        $this->ipAddress = (string)$ipAddress;
//    }
//
//    //-------------------------------------------------------------------------
//
//    /**
//     * @ORM\Column(type="string", name="user_agent", nullable=true)
//     * @var string
//     */
//    protected $userAgent;
//
//    /**
//     * @return string
//     */
//    public function getUserAgent()
//    {
//        return $this->userAgent;
//    }
//
//    /**
//     * @param string $userAgent
//     */
//    public function setUserAgent($userAgent)
//    {
//        $this->userAgent = (string)$userAgent;
//    }

    //-------------------------------------------------------------------------

    /**
     * UserAccessTokenEntity constructor.
     * @param UserEntity $user
     * @param string $hash
     */
    public function __construct(UserEntity $user, $hash)
    {
        $this->user = $user;
        $this->hash = (string)$hash;
//        $this->ipAddress = (new RemoteAddress())->setUseProxy()->getIpAddress();
//        $this->userAgent = (new HttpUserAgent())->getData();
    }
}
