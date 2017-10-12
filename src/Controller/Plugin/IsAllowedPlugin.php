<?php

namespace Adminaut\Controller\Plugin;

use Adminaut\Service\AccessControlService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class IsAllowedPlugin
 * @package Adminaut\Controller\Plugin
 */
class IsAllowedPlugin extends AbstractPlugin
{
    /**
     * @var AccessControlService
     */
    private $accessControlService;

    /**
     * IsAllowedPlugin constructor.
     * @param AccessControlService $accessControlService
     */
    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * @param $module
     * @param $permissionLevel
     * @param null $element
     * @param null $entity
     * @return bool
     */
    public function __invoke($module, $permissionLevel, $element = null, $entity = null)
    {
        return $this->accessControlService->isAllowed($module, $permissionLevel, $element, $entity);
    }
}
