<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Controller\UserController;
use Adminaut\Options\UserOptions;
use Adminaut\Service\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * Class UserControllerFactory
 * @package Adminaut\Controller\Factory
 */
class UserControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        return new UserController(
            $parentLocator->get(UserService::class),
            $parentLocator->get(UserOptions::class)
        );
    }
}
