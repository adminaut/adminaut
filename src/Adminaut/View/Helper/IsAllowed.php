<?php

namespace Adminaut\View\Helper;

use Adminaut\Service\AccessControl;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

/**
 * Class IsAllowed
 * @package Adminaut\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var AccessControl
     */
    protected $aclService;

    /**
     * @param $resource
     * @param $permission
     * @return bool
     */
    public function __invoke($module, $permission, $element = null, $entity = null)
    {
        return $this->getAclService()->isAllowed($module, $permission, $element, $entity);
    }

    /**
     * @return AccessControl
     */
    public function getAclService()
    {
        return $this->aclService;
    }

    /**
     * @param AccessControl $aclService
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
     * @return $this
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
}