<?php

namespace Adminaut\View\Helper\Factory;

use Adminaut\Options\AppearanceOptions;
use Adminaut\View\Helper\AppearanceViewHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AppearanceViewHelperFactory
 * @package Adminaut\View\Helper\Factory
 */
class AppearanceViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AppearanceViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AppearanceOptions $appearanceOptions */
        $appearanceOptions = $container->get(AppearanceOptions::class);

        return new AppearanceViewHelper($appearanceOptions);
    }
}
