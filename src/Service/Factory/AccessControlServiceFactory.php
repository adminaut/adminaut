<?php

namespace Adminaut\Service\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AccessControlServiceFactory
 * @package Adminaut\Service\Factory
 */
class AccessControlServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AccessControlService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        /** @var array $config */
        $config = $container->get('config');

        $roles = isset($config['adminaut']['roles']) ? $config['adminaut']['roles'] : [];

        return new AccessControlService($authenticationService, $roles);
    }
}
