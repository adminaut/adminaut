<?php

namespace Adminaut\Filesystem\Adapter\Factory;

use Adminaut\Options\AdminautOptions;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class PrivateAdapterFactory
 * @package Adminaut\Filesystem\Adapter\Factory
 */
class PrivateAdapterFactory implements FactoryInterface
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

        if (!isset($filesystemOptions['private'])) {
            throw new ServiceNotCreatedException('Configuration for private filesystem adapter could not be found.');
        }

        $private = $filesystemOptions['private'];

        if (!isset($private['adapter'])) {
            throw new ServiceNotCreatedException('Class for private filesystem adapter could not be found.');
        }

        if (!class_exists($private['adapter'])) {
            throw new ServiceNotCreatedException('Class for private filesystem adapter does not exists.');
        }

        $privateAdapterClass = $private['adapter'];

        if (!isset($private['options']) || !isset($private['options']['root'])) {
            throw new ServiceNotCreatedException('Options or root for private filesystem adapter could not be found.');
        }

        $privateAdapterRoot = $private['options']['root'];

        return new $privateAdapterClass($privateAdapterRoot);
    }
}
