<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\Acl;
use Adminaut\Service\AccessControlService;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class AclFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface|PluginManager $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var PluginManager $pluginManager */
        $pluginManager = $serviceLocator->getServiceLocator();

        /** @var AccessControlService $accessControlService */
        $accessControlService = $pluginManager->get(AccessControlService::class);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $pluginManager->get(AuthenticationService::class);

        return new Acl($accessControlService, $authenticationService);
    }
}
