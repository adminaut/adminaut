<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Service\AccessControlService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Adminaut\Authentication\Service\AuthenticationService;

/**
 * Class AclPlugin
 * @package Adminaut\Controller\Plugin
 */
class AclPlugin extends AbstractPlugin
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
     * AclPlugin constructor.
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

    /**
     * @return AccessControlService
     */
    public function getAcl()
    {
        return $this->accessControlService;
    }
}
