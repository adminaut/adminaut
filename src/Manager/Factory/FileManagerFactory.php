<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Options\FileManagerOptions;
use BsbFlysystem\Service\AdapterManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use League\Flysystem\Filesystem;
use Adminaut\Manager\FileManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FileManagerFactory
 * @package Adminaut\Manager\Factory
 */
class FileManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FileManager|null
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var FileManagerOptions $fileManagerOptions */
        $fileManagerOptions = $container->get(FileManagerOptions::class);

        /** @var AdapterManager $adapterManager */
        $adapterManager = $container->get(AdapterManager::class);

        /** @var Filesystem $fileSystemDefault */
        $fileSystemDefault = new Filesystem($adapterManager->get('default'));

        /** @var Filesystem $fileSystemCache */
        $fileSystemCache = new Filesystem($adapterManager->get('cache'));

        // todo: rewrite to constructor DI
        FileManager::setConstructParams(
            $entityManager,
            $fileManagerOptions->toArray(),
            $fileSystemDefault,
            $fileSystemCache
        );
        return FileManager::getInstance();
    }
}
