<?php

namespace Adminaut\Manager\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Adminaut\Manager\FileManager;
use League\Flysystem\Filesystem;
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
     * @return FileManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var Filesystem $privateFilesystem */
        $privateFilesystem = $container->get('adminautPrivateFilesystem');

        /** @var Filesystem $publicFilesystem */
        $publicFilesystem = $container->get('adminautPublicFilesystem');

        return new FileManager($entityManager, $privateFilesystem, $publicFilesystem);
    }
}
