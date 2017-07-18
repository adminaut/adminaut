<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Service\AccessControlService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Adminaut\Authentication\Service\AuthenticationService;

/**
 * Class Acl
 * @package Adminaut\Controller\Plugin
 */
class Acl extends AbstractPlugin
{

    /**
     * @var AccessControlService
     */
    private $accessControlService;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * Acl constructor.
     * @param AccessControlService $accessControlService
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AccessControlService $accessControlService, AuthenticationService $authenticationService)
    {
        $this->accessControlService = $accessControlService;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $module
     * @param $permission
     * @param null $element
     * @param null $entity
     * @return bool
     */
    public function isAllowed($module, $permission, $element = null, $entity = null)
    {
        if (!$this->accessControlService->getUser()) {
            $this->accessControlService->setUser($this->authenticationService->getIdentity());
        }

        return $this->accessControlService->isAllowed($module, $permission, $element, $entity);
    }
}
