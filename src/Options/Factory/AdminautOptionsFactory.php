<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminautOptionsFactory
 * @package Adminaut\Options\Factory
 */
class AdminautOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminautOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        $options = isset($config['adminaut']) ? $config['adminaut'] : [];

        return new AdminautOptions($options);
    }
}
