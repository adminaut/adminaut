<?php

namespace Adminaut\Datatype\View\Helper\Factory;

use Adminaut\Datatype\View\Helper\Datatype;
use Interop\Container\ContainerInterface;
use TwbBundle\Options\ModuleOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DatatypeFactory
 * @package Adminaut\Datatype\View\Helper\Factory
 */
class DatatypeFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Datatype
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $options */
        $twbBundleModuleOptions = $container->get(ModuleOptions::class);

        return new Datatype($twbBundleModuleOptions);
    }
}
