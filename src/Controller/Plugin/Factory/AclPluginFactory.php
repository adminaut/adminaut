<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\Plugin\AclPlugin;
use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AclPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class AclPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AclPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        return new AclPlugin($accessControlService, $authenticationService);
    }
}
