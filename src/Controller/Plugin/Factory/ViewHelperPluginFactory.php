<?php

namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Controller\Plugin\ViewHelperPlugin;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * Class ViewHelperPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class ViewHelperPluginFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ViewHelperPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var HelperPluginManager $viewHelperManager */
        $viewHelperManager = $container->get('ViewHelperManager');

        return new ViewHelperPlugin($viewHelperManager);
    }
}
