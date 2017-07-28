<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\IsAllowedPlugin;
use Adminaut\Service\AccessControlService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IsAllowedPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class IsAllowedPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return IsAllowedPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        return new IsAllowedPlugin($accessControlService);
    }
}
