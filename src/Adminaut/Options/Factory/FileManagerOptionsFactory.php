<?php
namespace Adminaut\Options\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class FileManagerOptionsFactory
 * @package Adminaut\Options\Factory
 */
class FileManagerOptionsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        return new \Adminaut\Options\FileManagerOptions(
            $config['file_manager']['params']
        );
    }
}