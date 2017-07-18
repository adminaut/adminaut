<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\View\Helper\UserIdentity;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

/**
 * Class UserIdentityFactory
 * @package Adminaut\View\Helper\Factory
 */
class UserIdentityFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface|HelperPluginManager $serviceLocator
     * @return UserIdentity
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $serviceLocator->getServiceLocator();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $helperPluginManager->get(AuthenticationService::class);

        return new UserIdentity($authenticationService);
    }
}
