<?php

namespace Adminaut\Filesystem\Factory;

use Adminaut\Options\AdminautOptions;
use Interop\Container\ContainerInterface;
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

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        $filesystemOptions = $adminautOptions->getFilesystem();

        $private = $filesystemOptions['private'];

        $privateAdapterClass = $private['adapter'];
        $privateAdapterRoot = $private['options']['root'];

        $privateAdapter = new $privateAdapterClass($privateAdapterRoot);

        return new Filesystem($privateAdapter);
    }
}
