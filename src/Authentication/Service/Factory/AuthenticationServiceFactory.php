<?php

namespace Adminaut\Authentication\Service\Factory;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Authentication\Storage\AuthStorage;
use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationServiceFactory
 * @package Adminaut\Authentication\Service\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthAdapter $adapter */
        $adapter = $container->get(AuthAdapter::class);

        /** @var AuthStorage $storage */
        $storage = $container->get(AuthStorage::class);

        $eventManager      = $container->get('EventManager');

        $as = new AuthenticationService($adapter, $storage);
        $as->setEventManager($eventManager);
        return $as;
    }
}
