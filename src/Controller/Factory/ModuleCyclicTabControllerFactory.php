<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ModuleCyclicTabController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModuleCyclicTabControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ModuleCyclicTabControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleCyclicTabController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ModuleCyclicTabController();
    }
}
