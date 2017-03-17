<?php

namespace Adminaut\Options;

use Adminaut\Entity\UserEntity;
use Zend\Stdlib\AbstractOptions;

/**
 * Class UserOptions
 * @package Adminaut\Options
 */
class UserOptions extends AbstractOptions
{
    /**
     * @var bool
     */
    protected $useRedirectParameterIfPresent = true;

    /**
     * @var string
     */
    protected $loginRedirectRoute = 'adminaut-dashboard';

    /**
     * @var string
     */
    protected $logoutRedirectRoute = 'adminaut-user/login';

    /**
     * @var bool
     */
    protected $enableUserStatus = false;

    /**
     * @var int
     */
    protected $defaultUserStatus = 1;

    /**
     * @var array
     */
    protected $allowedLoginStatus = [null, 1];

    /**
     * @var string
     */
    protected $userEntityClass = UserEntity::class;

    /**
     * @var array
     */
    protected $authAdapters = [100 => 'Adminaut\Authentication\Adapter\Db'];

    /**
     * @var array
     */
    protected $authIdentityFields = ['email'];

    /**
     * @var int
     */
    protected $passwordCost = 14;

    /**
     * @var bool
     */
    protected $enableDefaultEntities = true;

    /**
     * @return boolean
     */
    public function isUseRedirectParameterIfPresent()
    {
        return $this->useRedirectParameterIfPresent;
    }

    /**
     * @param boolean $useRedirectParameterIfPresent
     */
    public function setUseRedirectParameterIfPresent($useRedirectParameterIfPresent)
    {
        $this->useRedirectParameterIfPresent = $useRedirectParameterIfPresent;
    }

    /**
     * @return string
     */
    public function getLoginRedirectRoute()
    {
        return $this->loginRedirectRoute;
    }

    /**
     * @param string $loginRedirectRoute
     */
    public function setLoginRedirectRoute($loginRedirectRoute)
    {
        $this->loginRedirectRoute = $loginRedirectRoute;
    }

    /**
     * @return string
     */
    public function getLogoutRedirectRoute()
    {
        return $this->logoutRedirectRoute;
    }

    /**
     * @param string $logoutRedirectRoute
     */
    public function setLogoutRedirectRoute($logoutRedirectRoute)
    {
        $this->logoutRedirectRoute = $logoutRedirectRoute;
    }

    /**
     * @return boolean
     */
    public function isEnableUserStatus()
    {
        return $this->enableUserStatus;
    }

    /**
     * @param boolean $enableUserStatus
     */
    public function setEnableUserStatus($enableUserStatus)
    {
        $this->enableUserStatus = $enableUserStatus;
    }

    /**
     * @return int
     */
    public function getDefaultUserStatus()
    {
        return $this->defaultUserStatus;
    }

    /**
     * @param int $defaultUserStatus
     */
    public function setDefaultUserStatus($defaultUserStatus)
    {
        $this->defaultUserStatus = $defaultUserStatus;
    }

    /**
     * @return array
     */
    public function getAllowedLoginStatus()
    {
        return $this->allowedLoginStatus;
    }

    /**
     * @param array $allowedLoginStatus
     */
    public function setAllowedLoginStatus($allowedLoginStatus)
    {
        $this->allowedLoginStatus = $allowedLoginStatus;
    }

    /**
     * @return string
     */
    public function getUserEntityClass()
    {
        return $this->userEntityClass;
    }

    /**
     * @param string $userEntityClass
     */
    public function setUserEntityClass($userEntityClass)
    {
        $this->userEntityClass = $userEntityClass;
    }

    /**
     * @return array
     */
    public function getAuthAdapters()
    {
        return $this->authAdapters;
    }

    /**
     * @param array $authAdapters
     */
    public function setAuthAdapters($authAdapters)
    {
        $this->authAdapters = $authAdapters;
    }

    /**
     * @return array
     */
    public function getAuthIdentityFields()
    {
        return $this->authIdentityFields;
    }

    /**
     * @param array $authIdentityFields
     */
    public function setAuthIdentityFields($authIdentityFields)
    {
        $this->authIdentityFields = $authIdentityFields;
    }

    /**
     * @return int
     */
    public function getPasswordCost()
    {
        return $this->passwordCost;
    }

    /**
     * @param int $passwordCost
     */
    public function setPasswordCost($passwordCost)
    {
        $this->passwordCost = $passwordCost;
    }

    /**
     * @param $enableDefaultEntities
     * @return $this
     */
    public function setEnableDefaultEntities($enableDefaultEntities)
    {
        $this->enableDefaultEntities = $enableDefaultEntities;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnableDefaultEntities()
    {
        return $this->enableDefaultEntities;
    }
}