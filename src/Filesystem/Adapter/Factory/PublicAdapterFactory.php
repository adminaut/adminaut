<?php

namespace Adminaut\Filesystem\Adapter\Factory;

use Adminaut\Options\AdminautOptions;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PublicAdapterFactory
 * @package Adminaut\Filesystem\Adapter\Factory
 */
class PublicAdapterFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var AdminautOptions $adminautOptions */
        $adminautOptions = $container->get(AdminautOptions::class);

        $filesystemOptions = $adminautOptions->getFilesystem();

        if (!isset($filesystemOptions['public'])) {
            throw new ServiceNotCreatedException('Configuration for public filesystem adapter could not be found.');
        }

        $public = $filesystemOptions['public'];

        if (!isset($public['adapter'])) {
            throw new ServiceNotCreatedException('Class for public filesystem adapter could not be found.');
        }

        if (!class_exists($public['adapter'])) {
            throw new ServiceNotCreatedException('Class for public filesystem adapter does not exists.');
        }

        $publicAdapterClass = $public['adapter'];

        if (!isset($public['options']) || !isset($public['options']['root'])) {
            throw new ServiceNotCreatedException('Options or root for public filesystem adapter could not be found.');
        }

        $publicAdapterRoot = $public['options']['root'];

        return new $publicAdapterClass($publicAdapterRoot);
    }
}
