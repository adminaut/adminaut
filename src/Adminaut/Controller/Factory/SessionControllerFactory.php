<?php

namespace Adminaut\Controller\Factory;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Controller\SessionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SessionControllerFactory
 * @package Adminaut\Controller\Factory
 */
class SessionControllerFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SessionController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $parentLocator->get(AuthenticationService::class);

        return new SessionController($authenticationService);
    }
}
