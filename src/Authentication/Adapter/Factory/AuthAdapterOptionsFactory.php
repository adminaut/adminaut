<?php

namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AuthAdapterOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthAdapterOptionsFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
class AuthAdapterOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthAdapterOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var array $config */
        $config = $container->get('Config');

        if (
            isset($config['adminaut'][AuthAdapterOptions::CONFIG_KEY])
            && is_array($config['adminaut'][AuthAdapterOptions::CONFIG_KEY])
        ) {
            return new AuthAdapterOptions($config['adminaut'][AuthAdapterOptions::CONFIG_KEY]);
        }

        return new AuthAdapterOptions();
    }
}
