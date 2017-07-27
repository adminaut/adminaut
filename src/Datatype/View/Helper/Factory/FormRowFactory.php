<?php

namespace Adminaut\Datatype\View\Helper\Factory;

use Adminaut\Datatype\View\Helper\FormRow;
use Interop\Container\ContainerInterface;
use TwbBundle\Options\ModuleOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormRowFactory
 * @package Adminaut\Datatype\View\Helper\Factory
 */
class FormRowFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FormRow
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ModuleOptions $options */
        $twbBundleModuleOptions = $container->get(ModuleOptions::class);

        return new FormRow($twbBundleModuleOptions);
    }
}
