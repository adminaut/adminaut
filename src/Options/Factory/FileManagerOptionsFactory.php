<?php

namespace Adminaut\Options\Factory;

use Adminaut\Options\FileManagerOptions;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FileManagerOptionsFactory
 * @package Adminaut\Options\Factory
 */
class FileManagerOptionsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FileManagerOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');

        return new FileManagerOptions(
            $config['file_manager']['params']
        );
    }
}
