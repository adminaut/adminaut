<?php

namespace Adminaut\Service\Factory;

use Adminaut\Options\AdminautOptions;
use Adminaut\Service\ManifestService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ManifestServiceFactory
 * @package Adminaut\Service\Factory
 */
class ManifestServiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ManifestService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        return new ManifestService($adminautOptions->getManifest());
    }
}
