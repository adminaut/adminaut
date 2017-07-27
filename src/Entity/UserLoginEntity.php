<?php

namespace Adminaut\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Session\Validator\HttpUserAgent;

/**
 * Class UserLoginEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserLoginRepository")
 * @ORM\Table(name="adminaut_user_login")
 * @ORM\HasLifecycleCallbacks()
 */
class UserLoginEntity extends Base
{
    /**
     * Types.
     */
    const TYPE_FAILED = 0;
    const TYPE_SUCCESSFUL = 1;

    //-------------------------------------------------------------------------

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
     * @ORM\ManyToOne(targetEntity="UserEntity", inversedBy="logins")
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
     * @ORM\Column(type="integer", name="type", options={"default":0})
     * @var int
     */
    protected $type;

    public function getType()
    {
        return $this->type;
    }

    //-------------------------------------------------------------------------

    /**
     * @ORM\Column(type="string", name="ip_address", nullable=true)
     * @var string
     */
    protected $ipAddress;

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = (string)$ipAddress;
    }

    //-------------------------------------------------------------------------

    /**
     * @ORM\Column(type="string", name="user_agent", nullable=true)
     * @var string
     */
    protected $userAgent;

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = (string)$userAgent;
    }

    //-------------------------------------------------------------------------

    /**
     * UserLoginEntity constructor.
     * @param UserEntity $user
     * @param int $type
     */
    public function __construct(UserEntity $user, $type)
    {
        $this->user = $user;
        $this->type = $type;
        $this->ipAddress = (new RemoteAddress())->setUseProxy()->getIpAddress();
        $this->userAgent = (new HttpUserAgent())->getData();
    }
}
