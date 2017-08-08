<?php

namespace Adminaut\Filesystem\Factory;

use Interop\Container\ContainerInterface;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Filesystem;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PublicFilesystemFactory
 * @package Adminaut\Filesystem\Factory
 */
class PublicFilesystemFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Filesystem
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AbstractAdapter $publicAdapter */
        $publicAdapter = $container->get('adminautPublicFilesystemAdapter');

        return new Filesystem($publicAdapter);
    }
}
