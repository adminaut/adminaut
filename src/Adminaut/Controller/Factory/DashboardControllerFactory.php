<?php
namespace Adminaut\Controller\Factory;

use Adminaut\Controller\DashboardController;
use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Class DashboardControllerFactory
 * @package Adminaut\Controller\Factory
 */
class DashboardControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return DashboardController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new DashboardController(
            $parentLocator->get(AccessControlService::class),
            $parentLocator->get(\Doctrine\ORM\EntityManager::class)
        );
    }


}