<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\UserAuthentication;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAuthenticationFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class UserAuthenticationFactory implements FactoryInterface
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

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $pluginManager->get(AuthenticationService::class);

        return new UserAuthentication($authenticationService);
    }
}
