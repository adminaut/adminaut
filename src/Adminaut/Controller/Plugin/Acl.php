<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Service\AccessControlService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Acl
 * @package Adminaut\Controller\Plugin
 */
class Acl extends AbstractPlugin
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var AccessControlService
     */
    protected $aclService;

    /**
     * @param $resource
     * @param $permission
     * @return bool
     */
    public function isAllowed($module, $permission, $element = null, $entity = null)
    {
        if(!$this->getAclService()->getUser()) {
            $this->getAclService()->setUser($this->authService->getIdentity());
        }

        return $this->getAclService()->isAllowed($module, $permission, $element, $entity);
    }

    /**
     * @return AccessControlService
     */
    public function getAclService()
    {
        return $this->aclService;
    }

    /**
     * @param AccessControlService $aclService
     */
    public function setAclService($aclService)
    {
        $this->aclService = $aclService;
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
     * @return Acl
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}