<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Authentication\Adapter\AdapterChain as AuthAdapter;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAuthentication
 * @package Adminaut\Controller\Plugin
 */
class UserAuthentication extends AbstractPlugin
{
    /**
     * @var AuthAdapter
     */
    protected $authAdapter;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->getAuthService()->getIdentity();
    }

    /**
     * @return AuthAdapter
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     * @param AuthAdapter $authAdapter
     * @return UserAuthentication
     */
    public function setAuthAdapter(AuthAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
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
     * @return UserAuthentication
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}