<?php

namespace Adminaut\Form\View\Helper\Factory;

use Adminaut\Form\View\Helper\FormElement;
use Interop\Container\ContainerInterface;
use TwbBundle\Options\ModuleOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FormElementFactory
 * @package Adminaut\Form\View\Helper\Factory
 */
class FormElementFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FormElement
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var ModuleOptions $twbBundleModuleOptions */
        $twbBundleModuleOptions = $container->get(ModuleOptions::class);

        return new FormElement($twbBundleModuleOptions);
    }
}
