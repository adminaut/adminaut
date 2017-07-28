<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ModuleController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\FileManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModuleControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ModuleControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        // todo: add type hint
        $viewRenderer = $container->get('ViewRenderer');

        /** @var FileManager $fileManager */
        $fileManager = $container->get(FileManager::class);

        return new ModuleController(
            $entityManager,
            $moduleManager,
            $viewRenderer,
            $fileManager
        );
    }
}
