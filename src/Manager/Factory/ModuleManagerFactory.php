<?php

namespace Adminaut\Manager\Factory;

use Adminaut\Manager\ModuleManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModuleManagerFactory
 * @package Adminaut\Manager\Factory
 */
class ModuleManagerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ModuleManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        /** @var array $config */
        $config = $container->get('Config');

        $modules = isset($config['adminaut']['modules']) ? $config['adminaut']['modules'] : [];

        return new ModuleManager($entityManager, $modules);
    }
}
