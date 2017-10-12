<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\AuthenticationPlugin;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class AuthenticationPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        return new AuthenticationPlugin($authenticationService);
    }
}
