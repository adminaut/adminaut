<?php
namespace Adminaut\Controller\Plugin\Factory;


use Adminaut\Service\AccessControlService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclControllerPluginFactory
 * @package Adminaut\Controller\Plugin\Factory
 */
class AclControllerPluginFactory implements FactoryInterface
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

        $controllerPlugin = new \Adminaut\Controller\Plugin\Acl();
        $controllerPlugin->setAclService($parentLocator->get(AccessControlService::class));
        $controllerPlugin->setAuthService($parentLocator->get('UserAuthService'));
        return $controllerPlugin;
    }
}