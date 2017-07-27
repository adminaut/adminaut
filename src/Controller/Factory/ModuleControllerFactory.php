<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ModuleController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\FileManager;
use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\I18n\Translator\Translator;
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

        /** @var array $config */
        $config = $container->get('Config');

        /** @var AccessControlService $accessControlService */
        $accessControlService = $container->get(AccessControlService::class);

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var Translator $translator */
        $translator = $container->get(Translator::class);

        /** @var ModuleManager $moduleManager */
        $moduleManager = $container->get(ModuleManager::class);

        // todo: add type hint
        $viewRenderer = $container->get('ViewRenderer');

        /** @var FileManager $fileManager */
        $fileManager = $container->get(FileManager::class);

        return new ModuleController(
            $config,
            $accessControlService,
            $entityManager,
            $translator,
            $moduleManager,
            $viewRenderer,
            $fileManager
        );
    }
}
