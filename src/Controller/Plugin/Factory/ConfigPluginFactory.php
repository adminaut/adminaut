<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\ConfigPlugin;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConfigPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class ConfigPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConfigPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('Config');

        return new ConfigPlugin($config);
    }
}
