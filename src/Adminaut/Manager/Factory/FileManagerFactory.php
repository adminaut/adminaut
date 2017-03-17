<?php
namespace Adminaut\Manager\Factory;

use Adminaut\Options\FileManagerOptions;
use League\Flysystem\Filesystem;
use Adminaut\Manager\FileManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileManagerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        FileManager::setConstructParams(
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class),
            $serviceLocator->get(FileManagerOptions::class)->toArray(),
            new FileSystem($serviceLocator->get(\BsbFlysystem\Service\AdapterManager::class)->get('default')),
            new Filesystem($serviceLocator->get(\BsbFlysystem\Service\AdapterManager::class)->get('cache'))
        );
        return FileManager::getInstance();
    }
}