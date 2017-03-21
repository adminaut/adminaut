<?php
namespace Adminaut\Controller\Factory;


use Adminaut\Controller\InstallController;
use Adminaut\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class InstallControllerFactory
 * @package Adminaut\Controller\Factory
 */
class InstallControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return InstallController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        return new InstallController(
            $sm->get(UserService::class)
        );
    }

}