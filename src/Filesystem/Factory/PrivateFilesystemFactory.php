<?php

namespace Adminaut\Filesystem\Factory;

use Interop\Container\ContainerInterface;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Filesystem;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PrivateFilesystemFactory
 * @package Adminaut\Filesystem\Factory
 */
class PrivateFilesystemFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Filesystem
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AbstractAdapter $privateAdapter */
        $privateAdapter = $container->get('adminautPrivateFilesystemAdapter');

        return new Filesystem($privateAdapter);
    }
}
