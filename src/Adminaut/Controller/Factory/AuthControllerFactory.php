<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\AuthController;
use Adminaut\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthControllerFactory
 * @package Adminaut\Controller\Factory
 */
class AuthControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $parentLocator->get(AuthenticationService::class);

        /** @var UserService $userService */
        $userService = $parentLocator->get(UserService::class);

        return new AuthController($authenticationService, $userService);
    }
}
