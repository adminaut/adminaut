<?php

namespace Adminaut\Filesystem\Factory;

use Adminaut\Options\AdminautOptions;
use Interop\Container\ContainerInterface;
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

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        $filesystemOptions = $adminautOptions->getFilesystem();

        $public = $filesystemOptions['public'];

        $publicAdapterClass = $public['adapter'];
        $publicAdapterRoot = $public['options']['root'];

        $publicAdapter = new $publicAdapterClass($publicAdapterRoot);

        return new Filesystem($publicAdapter);
    }
}
