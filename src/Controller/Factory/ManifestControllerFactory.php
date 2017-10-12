<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ManifestController;
use Adminaut\Service\ManifestService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ManifestControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ManifestControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ManifestController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var ManifestService $manifestService */
        $manifestService = $container->get(ManifestService::class);

        return new ManifestController($manifestService);
    }
}
