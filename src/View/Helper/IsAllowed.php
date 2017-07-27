<?php

namespace Adminaut\View\Helper;

use Adminaut\Service\AccessControlService;
use Zend\View\Helper\AbstractHelper;

/**
 * Class IsAllowed
 * @package Adminaut\View\Helper
 */
class IsAllowed extends AbstractHelper
{
    /**
     * @var AccessControlService
     */
    private $accessControlService;

    /**
     * IsAllowed constructor.
     * @param AccessControlService $accessControlService
     */
    public function __construct(AccessControlService $accessControlService)
    {
        $this->accessControlService = $accessControlService;
    }

    /**
     * @param $module
     * @param $permission
     * @param null $element
     * @param null $entity
     * @return bool
     */
    public function __invoke($module, $permission, $element = null, $entity = null)
    {
        return $this->accessControlService->isAllowed($module, $permission, $element, $entity);
    }
}
