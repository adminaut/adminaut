<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Options\ManifestOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ManifestOptionsFactory
 * @package Adminaut\Options\Factory
 */
class ManifestOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ManifestOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new ManifestOptions($adminautOptions->getManifest());
    }
}
