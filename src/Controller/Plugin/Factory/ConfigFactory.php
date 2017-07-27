<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\Config;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConfigFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class ConfigFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Config
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('Config');

        return new Config($config);
    }
}
