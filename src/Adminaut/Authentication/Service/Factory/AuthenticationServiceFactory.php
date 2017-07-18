<?php

namespace Adminaut\Authentication\Service\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Authentication\Storage\ActiveLoginStorage;
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
        /** @var AuthAdapter $adapter */
        $adapter = $serviceLocator->get(AuthAdapter::class);

        /** @var ActiveLoginStorage $storage */
        $storage = $serviceLocator->get(ActiveLoginStorage::class);

        return new AuthenticationService($adapter, $storage);
    }
}
