<?php
namespace Adminaut\Manager\Factory;

use Adminaut\Manager\ModuleManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ModuleManagerFactory
 * @package Adminaut\Manager\Factory
 */
class ModuleManagerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ModuleManager(
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class)
        );
    }
}