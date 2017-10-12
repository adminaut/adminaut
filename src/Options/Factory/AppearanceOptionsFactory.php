<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Options\AppearanceOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AppearanceOptionsFactory
 * @package Adminaut\Options\Factory
 */
class AppearanceOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AppearanceOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new AppearanceOptions($adminautOptions->getAppearance());
    }
}
