<?php

namespace Adminaut\Navigation\Factory;

use Adminaut\Navigation\Navigation;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class NavigationFactory
 * @package Adminaut\Navigation\Factory
 */
class NavigationFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return (new Navigation())($container, $requestedName);
    }
}
