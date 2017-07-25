<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Service\AccessControlService;
use Adminaut\View\Helper\IsAllowed;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IsAllowedViewHelperFactory
 * @package Adminaut\Factory\View\Helper
 */
class IsAllowedViewHelperFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AccessControlService $accessControlService */
        $accessControlService = $parentLocator->get(AccessControlService::class);

        return new IsAllowed($accessControlService);
    }
}
