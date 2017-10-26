<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ModuleTabController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModuleTabControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ModuleTabControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleTabController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ModuleTabController();
    }
}
