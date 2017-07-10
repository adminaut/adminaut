<?php

namespace Adminaut\Authentication\Service\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Storage\CookieStorage;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthenticationServiceFactory
 * @package Adminaut\Authentication\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CookieStorage $storage */
        $storage = $serviceLocator->get(CookieStorage::class);

        /** @var AuthAdapter $adapter */
        $adapter = $serviceLocator->get(AuthAdapter::class);

        return new AuthenticationService($storage, $adapter);
    }
}
