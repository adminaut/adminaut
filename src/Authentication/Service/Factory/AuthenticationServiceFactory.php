<?php

namespace Adminaut\Authentication\Service\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Authentication\Storage\AuthStorage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthenticationServiceFactory
 * @package Adminaut\Authentication\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AuthAdapter $adapter */
        $adapter = $serviceLocator->get(AuthAdapter::class);

        /** @var AuthStorage $storage */
        $storage = $serviceLocator->get(AuthStorage::class);

        return new AuthenticationService($adapter, $storage);
    }
}
