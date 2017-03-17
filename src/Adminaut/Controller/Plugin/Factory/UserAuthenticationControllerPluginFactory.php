<?php
namespace Adminaut\Controller\Plugin\Factory;

use Adminaut\Authentication\Adapter\AdapterChain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserAuthenticationControllerPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class UserAuthenticationControllerPluginFactory implements FactoryInterface
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

        $authService = $parentLocator->get('UserAuthService');
        $authAdapter = $parentLocator->get(AdapterChain::class);
        $controllerPlugin = new \Adminaut\Controller\Plugin\UserAuthentication;
        $controllerPlugin->setAuthService($authService);
        $controllerPlugin->setAuthAdapter($authAdapter);
        return $controllerPlugin;
    }
}