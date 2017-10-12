<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\View\Helper\ConfigViewHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConfigViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class ConfigViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConfigViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var array $config */
        $config = $container->get('Config');

        return new ConfigViewHelper($config);
    }
}
