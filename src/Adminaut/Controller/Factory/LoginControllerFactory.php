<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\LoginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LoginControllerFactory
 * @package Adminaut\Controller\Factory
 */
class LoginControllerFactory implements FactoryInterface
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

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $parentLocator->get(AuthenticationService::class);

        return new LoginController($authenticationService);
    }
}
