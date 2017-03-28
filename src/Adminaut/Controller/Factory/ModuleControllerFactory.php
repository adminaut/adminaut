<?php
namespace Adminaut\Controller\Factory;

use Adminaut\Controller\ModuleController;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\FileManager;
use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ModuleControllerFactory
 * @package Adminaut\Controller\Factory
 */
class ModuleControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new ModuleController(
            $parentLocator->get('config'),
            $parentLocator->get(AccessControlService::class),
            $parentLocator->get(\Doctrine\ORM\EntityManager::class),
            $parentLocator->get(ModuleManager::class),
            $parentLocator->get('ViewRenderer'),
            $parentLocator->get(FileManager::class)
        );
    }
}